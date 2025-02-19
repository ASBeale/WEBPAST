<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Jabatan;
use App\Models\Kelompok;
use App\Models\Keanggotaan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeanggotaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Keanggotaan::query();

        // Filter
        if ($request->has('periode') && $request->input('periode') !== '') {
            $query->where('periodeID', $request->input('periode'));
        }

        // Search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('anggota', function($subQuery) use ($searchTerm) {
                    $subQuery->where('nama', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('kelompok', function($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_kelompok', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('jabatan', function($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_jabatan', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('anggota.role', function($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_role', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Sort dan pagination
        $keanggotaan = $query->with(['anggota', 'kelompok', 'jabatan', 'periode', 'kehadiranMisaAnggota'])
                            ->latest()
                            ->paginate(15);

        // Menambahkan perhitungan kehadiran untuk setiap anggota
        foreach ($keanggotaan as $anggota) {
            $anggota->jumlah_hadir = $anggota->kehadiranMisaAnggota()->where('status_kehadiran', 'Hadir')->count();
            $anggota->jumlah_ijin = $anggota->kehadiranMisaAnggota()->where('status_kehadiran', 'Ijin')->count();
            $anggota->jumlah_alpha = $anggota->kehadiranMisaAnggota()->where('status_kehadiran', 'Alpha')->count();
        }
       
        $keanggotaan->appends([
            'search' => $request->search,
            'periode' => $request->periode
        ]);

        return view('keanggotaan.index', [
            'keanggotaans' => $keanggotaan,
            'kelompoks' => Kelompok::all(),
            'jabatans' => Jabatan::all(),
            'periode' => Periode::where('status_periode', 1)->get(),
            'periodes' => Periode::all(),
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createSingle()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        
        // Cari periode aktif
        $periodeAktif = Periode::where('status_periode', 1)->first();
        
        $data = [
            'kelompoks' => Kelompok::all(),
            'anggotas' => Anggota::where('roleID', '!=', 1)
                            // filter untuk anggota yang belum punya keanggotaan pada periode yang sedang aktif
                            ->whereDoesntHave('keanggotaan', function($query) use ($periodeAktif) {
                                $query->where('periodeID', $periodeAktif->periodeID);
                            })
                            ->get(),
            'jabatans' => Jabatan::all(),
            'periodes' => Periode::all(),
        ];
        
        return view('keanggotaan.create-single', $data);
    }

    public function createMulti()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Cari periode aktif
        $periodeAktif = Periode::where('status_periode', 1)->first();

        $data = [
            'kelompoks' => Kelompok::all(),
            'anggotas' => Anggota::where('roleID', '!=', 1)
                            // filter untuk anggota yang belum punya keanggotaan pada periode yang sedang aktif
                            ->whereDoesntHave('keanggotaan', function($query) use ($periodeAktif) {
                                $query->where('periodeID', $periodeAktif->periodeID);
                            })
                            ->get(),
            'jabatans' => Jabatan::all(),
            'periodes' => Periode::all(),
        ];
        
        return view('keanggotaan.create-multi', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek admin
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // single input
        if ($request->has('anggota') && !is_array($request->anggota)) {
            $request->validate([
                'anggota' => 'required|exists:anggota,anggotaID',
                'kelompokID' => 'required|exists:kelompok,kelompokID',
                'periodeID' => 'required|exists:periode,periodeID',
            ]);

            $data = [
                'anggotaID' => $request->anggota,
                'kelompokID' => $request->kelompokID,
                'periodeID' => $request->periodeID
            ];

            // Tambahkan jabatan jika ada
            $jabatanField = 'jabatan_' . $request->anggota;
            if ($request->has($jabatanField)) {
                $data['jabatanID'] = $request->input($jabatanField);
            }

            Keanggotaan::create($data);
        } 
        
        // multi input
        else {
            $request->validate([
                'kelompokID' => 'required|exists:kelompok,kelompokID',
                'periodeID' => 'required|exists:periode,periodeID',
                'anggota' => 'required|array',
                'anggota.*' => 'exists:anggota,anggotaID',
            ]);

            foreach ($request->anggota as $anggotaID) {
                $data = [
                    'anggotaID' => $anggotaID,
                    'kelompokID' => $request->kelompokID,
                    'periodeID' => $request->periodeID
                ];

                // Tambahkan jabatan jika ada
                if (isset($request->jabatan[$anggotaID])) {
                    $data['jabatanID'] = $request->jabatan[$anggotaID];
                }

                Keanggotaan::create($data);
            }
        }

        return redirect('/keanggotaan')
            ->with('success', 'Anggota berhasil ditambahkan ke kelompok');
    }

    /**
     * Display the specified resource.
     */
    public function show(Keanggotaan $keanggotaan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('keanggotaan.show', [
            'keanggotaan' => $keanggotaan,
            'kelompok' => Kelompok::all(),
            'jabatan' => Jabatan::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keanggotaan $keanggotaan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('keanggotaan.edit', [
            'keanggotaan' => $keanggotaan,
            'kelompoks' => Kelompok::all(),
            'jabatans' => Jabatan::all(),
            'periodes' => Periode::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keanggotaan $keanggotaan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $validatedData = $request->validate([
            'kelompokID' => 'required|exists:kelompok,kelompokID',
            'jabatan' => 'nullable|exists:jabatan,jabatanID'
        ]);

        // Periksa apakah keanggotaan terkait dengan data lain
        if ($keanggotaan->kehadiranMisaAnggota()->exists()) {
            return redirect()->route('keanggotaan.index')
                ->with('error', 'Tidak dapat diubah! terdapat data kehadirannya');
        }

        $updateData = [
            'kelompokID' => $validatedData['kelompokID']
        ];

        if (isset($validatedData['jabatan'])) {
            $updateData['jabatanID'] = $validatedData['jabatan'];
        }

        $keanggotaan->update($updateData);

        return redirect()->route('keanggotaan.index')->with('success', 'Keanggotaan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keanggotaan $keanggotaan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah keanggotaan terkait dengan data lain
        if ($keanggotaan->kehadiranMisaAnggota()->exists()) {
            return redirect()->route('keanggotaan.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }

        // Hapus keanggotaan
        $keanggotaan->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('keanggotaan.index')
            ->with('success', 'Data keanggotaan berhasil dihapus');
    }

    public function rollingKelompok()
    {
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Cari periode aktif
        $periodeAktif = Periode::where('status_periode', 1)->first();
        if (!$periodeAktif) {
            return redirect()->route('keanggotaan.index')
                ->with('error', 'Tidak ada periode aktif');
        }

        // Ambil anggota-anggota yang belum memiliki keanggotaan di periode yang sedang aktif aktif
        $anggotas = Anggota::where('roleID', 2)  
                ->where('status_anggota', true)  
                ->whereDoesntHave('keanggotaan', function($query) use ($periodeAktif) {
                    $query->where('periodeID', $periodeAktif->periodeID);
                })
                ->get();

        // Ambil semua kelompok
        $kelompoks = Kelompok::all();

        // Jika tidak ada kelompok atau anggota, kembalikan error
        if ($kelompoks->isEmpty() || $anggotas->isEmpty()) {
            return redirect()->route('keanggotaan.index')
                ->with('error', 'Tidak ada kelompok atau anggota yang dapat di rolling di periode ini');
        }

        // Hitung jumlah anggota per kelompok
        $jumlahAnggotaPerKelompok = ceil($anggotas->count() / $kelompoks->count());

        // Proses rolling
        $anggotas = $anggotas->shuffle(); // Acak urutan anggota
        $rollingData = [];

        foreach ($kelompoks as $index => $kelompok) {
            $start = $index * $jumlahAnggotaPerKelompok;
            $slicedAnggota = $anggotas->slice($start, $jumlahAnggotaPerKelompok);

            foreach ($slicedAnggota as $anggota) {
                $rollingData[] = [
                    'anggotaID' => $anggota->anggotaID,
                    'kelompokID' => $kelompok->kelompokID,
                    'periodeID' => $periodeAktif->periodeID,
                    'jabatanID' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Simpan data keanggotaan
        Keanggotaan::insert($rollingData);

        return redirect()->route('keanggotaan.index')
            ->with('success', 'Rolling kelompok berhasil dilakukan');
    }
}
