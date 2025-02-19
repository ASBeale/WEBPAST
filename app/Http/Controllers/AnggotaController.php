<?php
namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        
        $query = Anggota::query();

        // Filter
        if ($request->has('status')) {
            $status = $request->input('status');
            
            // Hanya filter jika status adalah 0 atau 1 (0 tidak aktif, 1 aktif)
            if (in_array($status, ['0', '1'])) {
                $query->where('status_anggota', $status);
            }
        }

        // search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                ->orWhere('TelpNo', 'like', '%' . $searchTerm . '%')
                ->orWhere('status_anggota', 'like', '%' . $searchTerm . '%');
            });
        }

        $anggotas = $query->latest()->paginate(15);

        $anggotas->appends([
            'search' => $request->search,
            'status' => $request->status
        ]);

        return view('anggotas.index', [
            'anggotas' => $anggotas,
            'role' => Role::all(),
            'search' => $request->search,
            'status' => $request->status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('anggotas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:anggota,username',
            'nama' => 'required|string|max:255',
            'DoB' => 'required|date',
            'TelpNo' => 'required|regex:/^[0-9]{1,15}$/|max:15',
            'ortu_nama' => 'nullable|string|max:255',
            'ortu_telp_no' => 'nullable|regex:/^[0-9]{1,15}$/|max:15',
            'tanggal_bergabung' => 'required|date',
            'password' => 'nullable|string|min:6', 
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        // setiap membuat anggota baru, role awalnya anggota, bisa diubah rolenya di menu kelola role
        $validatedData['roleID'] = 2;

        Anggota::create($validatedData);

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Anggota $anggota)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('anggotas.show', [
            'anggota' => $anggota
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anggota $anggota)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('anggotas.edit', [
            'anggota' => $anggota,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anggota $anggota)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Cek apakah mencoba menonaktifkan akun sendiri
        if (Auth::id() === $anggota->anggotaID && $request->input('status_anggota') == 0) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        // Validasi data
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:anggota,username,' . $anggota->anggotaID . ',anggotaID',
            'nama' => 'required|string|max:255',
            'DoB' => 'required|date',
            'TelpNo' => 'required|regex:/^[0-9]{1,15}$/|max:15',
            'ortu_nama' => 'nullable|string|max:255',
            'ortu_telp_no' => 'nullable|regex:/^[0-9]{1,15}$/|max:15',
            'tanggal_bergabung' => 'required|date',
            'password' => 'nullable|string|min:6', 
            'status_anggota' => 'nullable|boolean',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

         // ubah aktif atau tidak aktif
        $validatedData['status_anggota'] = $request->has('status_anggota') ? 1 : 0;
    
        $anggota->update($validatedData);

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggota $anggota)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // larang kalau ingin hapus akun sendiri
        if ($anggota->anggotaID === Auth::id()) {
            return redirect()->route('anggotas.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Menghitung jumlah admin
        $adminCount = Anggota::where('roleID', 1)->count();

        // Kalau admin hanya tersisa 1 dan ingin dihapus, maka tampilkan error
        if ($anggota->roleID === 1 && $adminCount <= 1) {
            return redirect()->route('anggotas.index')->with('error', 'Tidak dapat menghapus anggota admin, karena harus ada minimal satu admin.');
        }

        // Periksa apakah anggota terkait dengan data lain
        if ($anggota->keanggotaan()->exists()) {
            return redirect()->route('anggotas.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain! (Tips: Non-Aktifkan Anggota apabila tidak melanjutkan keanggotaannya)');
        }

        $anggota->delete();

        return redirect()->route('anggotas.index')->with('success', 'Anggota berhasil dihapus');
    }
}
