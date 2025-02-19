<?php
namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Kelompok;
use App\Models\JadwalMisa;
use App\Models\Keanggotaan;
use Illuminate\Http\Request;
use App\Models\KehadiranMisa;
use Illuminate\Support\Carbon;
use App\Models\KehadiranMisaAnggota;
use Illuminate\Support\Facades\Auth;

class KehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
    
        $user = Auth::user();
        
        // Ambil periode aktif
        $periodeAktif = Periode::where('status_periode', 1)->first();

        if (!$periodeAktif) {
            return redirect()->back()->with('error', 'Tidak ada periode aktif');
        }

        // Ambil keanggotaan pengguna di periode aktif
        $keanggotaan = Keanggotaan::where('anggotaID', $user->anggotaID)
            ->where('periodeID', $periodeAktif->periodeID)
            ->with('kelompok')
            ->first();

        if (!$keanggotaan) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar dalam kelompok di periode ini');
        }

        $kelompokID = $keanggotaan->kelompok->kelompokID;

        // Query untuk kehadiran misa
        $query = KehadiranMisa::query();

        // Filter berdasarkan kelompok pengurus dan periode aktif
        $query->whereHas('jadwalMisa', function ($q) use ($periodeAktif, $kelompokID) {
            $q->where('periodeID', $periodeAktif->periodeID)
            ->where('kelompokID', $kelompokID); // Filter kelompok
        });

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('jadwalMisa', function($q) use ($searchTerm) {
                $q->whereHas('jenisMisa', function($subQuery) use ($searchTerm) {
                    $subQuery->where('jenis_misa', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('kehadiranMisa', function($subQuery) use ($searchTerm) {
                    $subQuery->where('pengisi_kehadiran_misa', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        if ($request->filled('start') && $request->filled('end')) {
            $query->whereHas('jadwalMisa', function($q) use ($request) {
                $q->whereBetween('tanggal_jam_misa', [
                    Carbon::parse($request->start)->startOfDay(), 
                    Carbon::parse($request->end)->endOfDay()
                ]);
            });
        }

        // Sort dan Paginasi
        $kehadiranmisas = $query->with([
            'jadwalMisa.jenisMisa', 
            'jadwalMisa.kelompok', 
            'jadwalMisa.periode',
            'kehadiranMisaAnggota.keanggotaan.anggota'
        ])->latest()->paginate(10);

        $kehadiranmisas->appends([
            'search' => $request->search,
            'start' => $request->start,
            'end' => $request->end
        ]);


        return view('kehadiran.index', [
            'kehadiranmisas' => $kehadiranmisas,
            'kelompok' => $keanggotaan->kelompok, // Mengambil kelompok dari keanggotaan
            'periode' => $periodeAktif, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
       // Authorization
       if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
    
        // Ambil periode aktif dari database
        $periodeAktif = Periode::where('status_periode', true)->first();

        $jadwalmisas = JadwalMisa::with('jenisMisa')
        // Tambahkan filter periode aktif
        ->where('periodeID', $periodeAktif->periodeID)
        ->when($request->kelompokID, function ($query) use ($request) {
            return $query->whereHas('kelompok', function ($q) use ($request) {
                $q->where('kelompokID', $request->kelompokID);
            });
        })
        // Filter out jadwal misa yang sudah pernah dibuat kehadirannya
        ->whereNotIn('jadwalMisaID', function($query) {
            $query->select('jadwalMisaID')
                ->from('kehadiran_misa');
        })
        ->get();

        $keanggotaans = Keanggotaan::with('anggota')
        // Tambahkan filter periode aktif
        ->where('periodeID', $periodeAktif->periodeID)
        ->when($request->kelompokID, function ($query) use ($request) {
            return $query->whereHas('kelompok', function ($q) use ($request) {
                $q->where('kelompokID', $request->kelompokID);
            });
        })
        ->get();

        $keanggotaans2 = Keanggotaan::with('anggota')
        // Filter periode aktif
        ->where('periodeID', $periodeAktif->periodeID)
        ->when($request->kelompokID, function ($query) use ($request) {
            // Tampilkan keanggotaan yang bukan bagian dari kelompok yang dikelola
            return $query->whereDoesntHave('kelompok', function ($q) use ($request) {
                $q->where('kelompokID', $request->kelompokID);
            });
        })
        ->get();
        
        return view('kehadiran.create', [
            'jadwalmisas' => $jadwalmisas,
            'periodeAktif' => $periodeAktif,
            'keanggotaans'=> $keanggotaans,
            'keanggotaans2'=> $keanggotaans2
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $validatedData = $request->validate([
            'jadwalMisaID' => 'required|exists:jadwal_misa,jadwalMisaID',
            'pengisi_kehadiran_misa' => 'required|string|max:255',
            'keanggotaanID' => 'required|array',
            'keanggotaanID.*' => 'exists:keanggotaan,keanggotaanID',
            'status_kehadiran' => 'nullable|array',
            'status_kehadiran.*' => 'nullable|in:hadir,ijin,alpha',
            'alasan_ijin' => 'nullable|array',
            'alasan_ijin.*' => 'nullable|string|max:255',
        ]);

        $jadwalMisa = JadwalMisa::with('jenisMisa')->findOrFail($validatedData['jadwalMisaID']);

        $kehadiranMisa = KehadiranMisa::create([
            'jadwalMisaID' => $jadwalMisa->jadwalMisaID,
            'pengisi_kehadiran_misa' => $validatedData['pengisi_kehadiran_misa'],
            'nama_misa' => $jadwalMisa->jenisMisa->nama_misa,
            'tanggal_jam_misa' => $jadwalMisa->tanggal_jam_misa,
        ]);

        // KehadiranMisaAnggota
        foreach ($validatedData['keanggotaanID'] as $index => $keanggotaanID) {
            $keanggotaan = Keanggotaan::with('anggota.role')->findOrFail($keanggotaanID);

            KehadiranMisaAnggota::create([
                'kehadiranMisaID' => $kehadiranMisa->kehadiranMisaID,
                'keanggotaanID' => $keanggotaanID,
                'status_kehadiran' => $validatedData['status_kehadiran'][$index] ?? null,
                'sebagai' => $keanggotaan->anggota->role->nama_role, 
                'alasan_ijin' => $validatedData['alasan_ijin'][$index] ?? null,
            ]);
        }

        return redirect()->route('kehadiran.index')
            ->with('success', 'Kehadiran Misa berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($kehadiranMisaID)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $kehadiranmisa = KehadiranMisa::with([
            'jadwalMisa.jenisMisa', 
            'jadwalMisa.kelompok',
            'kehadiranMisaAnggota.keanggotaan.anggota'
        ])->find($kehadiranMisaID);

        $kehadiranByStatus = [
            'hadir' => $kehadiranmisa->kehadiranMisaAnggota
                ->where('status_kehadiran', 'hadir')
                ->filter(function ($kehadiran) use ($kehadiranmisa) {
                    // Memastikan anggota berasal dari kelompok yang tidak sesuai dengan kelompokID pada kehadiranmisa
                    return $kehadiran->keanggotaan->kelompokID == $kehadiranmisa->jadwalMisa->kelompokID;
                }),

            'ijin' => $kehadiranmisa->kehadiranMisaAnggota->where('status_kehadiran', 'ijin'),
            'alpha' => $kehadiranmisa->kehadiranMisaAnggota->where('status_kehadiran', 'alpha')
        ];

        $kehadiranByStatus2 = [
            'hadir' => $kehadiranmisa->kehadiranMisaAnggota
                ->where('status_kehadiran', 'hadir')
                ->filter(function ($kehadiran) use ($kehadiranmisa) {
                    // Memastikan anggota berasal dari kelompok yang tidak sesuai dengan kelompokID pada kehadiranmisa
                    return $kehadiran->keanggotaan->kelompokID != $kehadiranmisa->jadwalMisa->kelompokID;
                })
        ];

        return view('kehadiran.show', [
            'kehadiranmisa' => $kehadiranmisa,
            'kehadiranByStatus' => $kehadiranByStatus,
            'kehadiranByStatus2' => $kehadiranByStatus2
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kehadiranMisaID)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Mengambil data kehadiran misa beserta relasinya
        $kehadiranMisa = KehadiranMisa::with([
            'jadwalMisa.jenisMisa', 
            'kehadiranMisaAnggota'
        ])->findOrFail($kehadiranMisaID);
        
        // Mengambil periode dari jadwal misa
        $periodeAktif = Periode::findOrFail($kehadiranMisa->jadwalMisa->periodeID);
        
        // Mengambil data kelompok
        $kelompok = Kelompok::findOrFail($kehadiranMisa->jadwalMisa->kelompokID);
        
        // Mengambil data keanggotaan untuk kelompok dan periode jadwal misa
        $keanggotaans = Keanggotaan::where('kelompokID', $kelompok->kelompokID)
            ->where('periodeID', $periodeAktif->periodeID)
            ->with('anggota')
            ->get();

        // Mengambil data keanggotaan untuk kelompok dan periode jadwal misa
        $keanggotaans2 = Keanggotaan::where('periodeID', $periodeAktif->periodeID)
        ->whereDoesntHave('kehadiranMisaAnggota', function ($query) use ($kelompok) {
            $query->where('kelompokID', $kelompok->kelompokID);
        })
        ->with('anggota')
        ->get();

        return view('kehadiran.edit', compact(
            'kehadiranMisa', 
            'kelompok', 
            'keanggotaans', 
            'keanggotaans2', 
            'periodeAktif'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kehadiranMisaID)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $validatedData = $request->validate([
            'keanggotaanID' => 'required|array',
            'keanggotaanID.*' => 'exists:keanggotaan,keanggotaanID',
            'status_kehadiran' => 'nullable|array',
            'status_kehadiran.*' => 'nullable|in:hadir,ijin,alpha',
            'alasan_ijin' => 'nullable|array',
            'alasan_ijin.*' => 'nullable|string|max:255',
        ]);

        // Ambil KehadiranMisa yang akan diupdate
        $kehadiranMisa = KehadiranMisa::findOrFail($kehadiranMisaID);

        // Proses update kehadiran anggota
        $kehadiranMisaAnggota = [];
        foreach ($validatedData['keanggotaanID'] as $index => $keanggotaanID) {
            $keanggotaan = Keanggotaan::with('anggota.role')->findOrFail($keanggotaanID);

            $kehadiranMisaAnggota[] = [
                'kehadiranMisaID' => $kehadiranMisa->kehadiranMisaID,
                'keanggotaanID' => $keanggotaanID,
                'status_kehadiran' => $validatedData['status_kehadiran'][$index] ?? null,
                'sebagai' => $keanggotaan->anggota->role->nama_role, 
                'alasan_ijin' => $validatedData['alasan_ijin'][$index] ?? null,
            ];
        }

        // Hapus data lama dan masukkan data baru
        $kehadiranMisa->kehadiranMisaAnggota()->delete();
        KehadiranMisaAnggota::insert($kehadiranMisaAnggota);

        return redirect()->route('kehadiran.index')
            ->with('success', 'Kehadiran Misa berhasil diperbarui.');
    }
}
