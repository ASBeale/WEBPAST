<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisKegiatan;
use Illuminate\Support\Facades\Auth;

class JenisKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        $query = JenisKegiatan::query();

        if (request('search')) {
            $query->where('jenis_kegiatan', 'like', '%' . request('search') . '%');
        }

        $jeniskegiatans = $query->paginate(10);

        return view('jeniskegiatan.index', compact('jeniskegiatans'));
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
        return view('jeniskegiatan.create');
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
            'jenis_kegiatan' => 'required|max:255',
        ]);

        JenisKegiatan::create($validatedData);

        return redirect()->route('jeniskegiatan.index')->with('success', 'Jenis kegiatan berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisKegiatan $jeniskegiatan)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisKegiatan $jeniskegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('jeniskegiatan.edit', [
            'jeniskegiatan' => $jeniskegiatan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisKegiatan $jeniskegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        // Validasi input
        $rules = [
            'jenis_kegiatan' => 'required|max:255',
        ];

        if ($jeniskegiatan->jadwalKegiatan()->exists()) {
            return redirect()->route('jeniskegiatan.index')
                ->with('error', 'Tidak dapat diubah! Jenis Kegiatan memiliki data yang berkaitan');
        }

        $validatedData = $request->validate($rules);

        Jeniskegiatan::where('jenisKegiatanID', $jeniskegiatan->jenisKegiatanID)
            ->update($validatedData);

        return redirect()->route('jeniskegiatan.index')->with('success', 'Jenis kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisKegiatan $jeniskegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        if ($jeniskegiatan->jadwalKegiatan()->exists()) {
            return redirect()->route('jeniskegiatan.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }

        $jeniskegiatan->delete();
        return redirect()->route('jeniskegiatan.index')
            ->with('success', 'Jenis kegiatan berhasil dihapus');
    }
}
