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

class KehadiranMisaController extends Controller
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

        $query = KehadiranMisa::query();

        // Filter Periode
        if ($request->filled('periode')) {
            $query->whereHas('jadwalMisa', function($q) use ($request) {
                $q->where('periodeID', $request->periode);
            });
        }

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('jadwalMisa', function($q) use ($searchTerm) {
                $q->whereHas('jenisMisa', function($subQuery) use ($searchTerm) {
                    $subQuery->where('jenis_misa', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('kelompok', function($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_kelompok', 'like', '%' . $searchTerm . '%');
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
            'periode' => $request->periode,
            'start' => $request->start,
            'end' => $request->end
        ]);

        $periodeAktif = Periode::where('status_periode', 1)->get();
        $semuaperiode = Periode::all();

        return view('kehadiranmisa.index', [
            'kehadiranmisas' => $kehadiranmisas,
            'kelompoks' => Kelompok::all(),
            'periode' => $periodeAktif, 
            'periodes' => $semuaperiode 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
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

        
        return view('kehadiranmisa.create', [
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
        if (!Auth::check() || Auth::user()->roleID !== 1) {
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

        // Fetch JadwalMisa
        $jadwalMisa = JadwalMisa::with('jenisMisa')->findOrFail($validatedData['jadwalMisaID']);

        // KehadiranMisa
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

        return redirect()->route('kehadiranmisa.index')
            ->with('success', 'Kehadiran Misa berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KehadiranMisa $kehadiranmisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Load necessary relationships
        $kehadiranmisa->load([
            'jadwalMisa',
            'kehadiranMisaAnggota.keanggotaan.anggota'
        ]);

        // Group anggota by status kehadiran
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
        
        

        return view('kehadiranmisa.show', [
            'kehadiranmisa' => $kehadiranmisa,
            'kehadiranByStatus' => $kehadiranByStatus,
            'kehadiranByStatus2' => $kehadiranByStatus2,
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kehadiranMisaID)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
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


        return view('kehadiranmisa.edit', compact(
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
        if (!Auth::check() || Auth::user()->roleID !== 1) {
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

        return redirect()->route('kehadiranmisa.index')
            ->with('success', 'Kehadiran Misa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KehadiranMisa $kehadiranmisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $kehadiranmisa->delete();

        return redirect()->route('kehadiranmisa.index')->with('success', 'Kehadiran Misa berhasil dihapus.');
    }
}
