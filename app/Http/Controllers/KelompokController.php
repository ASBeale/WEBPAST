<?php
namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kelompok;
use App\Models\Keanggotaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelompokController extends Controller
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
        $query = Kelompok::query();

        if (request('search')) {
            $query->where('nama_kelompok', 'like', '%' . request('search') . '%');
        }

        $kelompoks = $query->paginate(10);

        return view('kelompoks.index', compact('kelompoks'));
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
        return view('kelompoks.create');
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
            'nama_kelompok' => 'required|max:255',
        ]);

        Kelompok::create($validatedData);

        return redirect()->route('kelompoks.index')->with('success', 'Kelompok berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelompok $kelompok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelompok $kelompok)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('kelompoks.edit', [
            'kelompok' => $kelompok
        ]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $rules = [
            'nama_kelompok' => 'required|max:255',
        ];

        if ($kelompok->jadwalMisas()->exists() || $kelompok->keanggotaans()->exists()) {
            return redirect()->route('kelompoks.index')
                ->with('error', 'Tidak dapat diubah! kelompok memiliki data yang berkaitan');
        }

        $validatedData = $request->validate($rules);

        Kelompok::where('kelompokID', $kelompok->kelompokID)
            ->update($validatedData);

        return redirect()->route('kelompoks.index')->with('success', 'Kelompok berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelompok $kelompok)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        if ($kelompok->jadwalMisas()->exists()|| $kelompok->keanggotaans()->exists()) {
            return redirect()->route('kelompoks.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }

        $kelompok->delete();
        return redirect()->route('kelompoks.index')
            ->with('success', 'Kelompok berhasil dihapus');
    }

    // pengurus
    // untuk menampilkan data kelompok di halaman pengurus
    public function datakelompok()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
    
        // Mengambil user yang sedang login
        $pengurus = Auth::user();
    
        // Cari keanggotaan aktif saat ini untuk pengurus
        $keanggotaanPengurus = Keanggotaan::with(['kelompok', 'periode'])
            ->where('anggotaID', $pengurus->anggotaID)
            ->whereHas('periode', function($query) {
                $query->where('status_periode', true);
            })
            ->first();
    
        if (!$keanggotaanPengurus) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki keanggotaan aktif.');
        }

        // Ambil semua anggota dalam kelompok dan periode yang sama
        $anggota = Keanggotaan::with(['anggota', 'jabatan', 'kehadiranMisaAnggota'])
        ->where('kelompokID', $keanggotaanPengurus->kelompokID)
        ->whereHas('periode', function($query) {
            $query->where('status_periode', true);
        })
        ->get();

        // Menambahkan perhitungan kehadiran untuk setiap anggota
        foreach ($anggota as $anggotaa) {
            $anggotaa->jumlah_hadir = $anggotaa->kehadiranMisaAnggota()->where('status_kehadiran', 'Hadir')->count();
            $anggotaa->jumlah_ijin = $anggotaa->kehadiranMisaAnggota()->where('status_kehadiran', 'Ijin')->count();
            $anggotaa->jumlah_alpha = $anggotaa->kehadiranMisaAnggota()->where('status_kehadiran', 'Alpha')->count();
        }
    
        return view('datakelompok.index', [
            'anggotas' => $anggota,
            'namaKelompok' => $keanggotaanPengurus->kelompok->nama_kelompok
        ]);
    }
}
