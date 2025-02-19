<?php
namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Kelompok;
use App\Models\JenisMisa;
use App\Models\JadwalMisa;
use App\Models\Keanggotaan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class JadwalMisaController extends Controller
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

        $query = JadwalMisa::query();

        // Filter Periode
        if ($request->filled('periode')) {
            $query->where('periodeID', $request->periode);
        }

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('jenismisa', function($subQuery) use ($searchTerm) {
                    $subQuery->where('jenis_misa', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('kelompok', function($subQuery) use ($searchTerm) {
                    $subQuery->where('nama_kelompok', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Filter Rentang Tanggal
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('tanggal_jam_misa', [
                Carbon::parse($request->start)->startOfDay(), 
                Carbon::parse($request->end)->endOfDay()
            ]);
        }

        // Sort dan Paginasi
        $jadwalmisas = $query->with(['jenismisa', 'kelompok', 'periode'])
            ->latest()
            ->paginate(10);

            $jadwalmisas->appends([
                'search' => $request->search,
                'periode' => $request->periode,
                'start' => $request->start,
                'end' => $request->end
            ]);


        $kelompoks = Kelompok::all();
        $jenismisas = JenisMisa::all();
        $periodeAktif = Periode::where('status_periode', 1)->get();
        $semuaperiode = Periode::all();

        return view('jadwalmisa.index', [
            'jadwalmisas' => $jadwalmisas,
            'kelompoks' => $kelompoks,
            'jenismisas' => $jenismisas,
            'periode' => $periodeAktif, 
            'periodes' => $semuaperiode 
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

        return view('jadwalmisa.create', [
            'jadwalmisas' => JadwalMisa::all(),
            'kelompoks' => Kelompok::all(),
            'jenismisas' => JenisMisa::all(),
            'periodes' => Periode::all(),
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
            'jenisMisaID' => 'required|exists:jenis_misa,jenisMisaID',
            'kelompokID' => 'required|exists:kelompok,kelompokID',
            'periodeID' => 'required|exists:periode,periodeID',
            'tanggal_jam_misa' => 'required|date',
        ]);

        JadwalMisa::create($validatedData);

        return redirect()->route('jadwalmisa.index')->with('success', 'Jadwal Misa berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalMisa $jadwalmisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('jadwalmisa.show', [
            'jadwalmisa' => $jadwalmisa
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalMisa $jadwalmisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('jadwalmisa.edit', [
            'jadwalmisa' => $jadwalmisa,
            'kelompoks' => Kelompok::all(),
            'jenismisas' => JenisMisa::all(),
            'periodes' => Periode::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalMisa $jadwalmisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah jadwal misa berkaitan dengan data lain
        // kalau sudah ada kehadiranMisa nya maka ga bisa diupdate semisal ga bisa ganti kelompok
        if ($jadwalmisa->kehadiranMisa()->exists()) {
            return redirect()->route('jadwalmisa.index')
                ->with('error', 'Tidak dapat diubah! terdapat data kehadirannya');
        }
        
        $rules = [
            'jenisMisaID' => 'required|exists:jenis_misa,jenisMisaID',
            'kelompokID' => 'required|exists:kelompok,kelompokID',
            'periodeID' => 'required|exists:periode,periodeID',
            'tanggal_jam_misa' => 'required|date',
        ];

        $validatedData = $request->validate($rules);

        $jadwalmisa->update($validatedData);

        return redirect()->route('jadwalmisa.index')->with('success', 'Jadwal Misa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalMisa $jadwalmisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah jadwal misa berkaitan dengan data lain
        if ($jadwalmisa->kehadiranMisa()->exists()) {
            return redirect()->route('jadwalmisa.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }

        $jadwalmisa->delete();
        return redirect()->route('jadwalmisa.index')->with('success', 'Jadwal Misa berhasil dihapus');
    }

    // untuk lihat jadwal di halaman pengurus lihat jadwal
    /**
     * Display a listing of the resource.
     */
    public function LihatJadwal()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 3 && Auth::user()->roleID !== 4) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
    

        // Mengambil user yang sedang login
        $user = Auth::user();

        // Cari periode aktif
        $periodeAktif = Periode::where('status_periode', 1)->first();

        // Cari keanggotaan user di periode aktif
        $keanggotaan = Keanggotaan::where('anggotaID', $user->anggotaID)
            ->where('periodeID', $periodeAktif->periodeID)
            ->first();

        // Jika tidak ada keanggotaan di periode yang sedang aktif
        if (!$keanggotaan) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar dalam keanggotaan periode aktif.');
        }

        $kelompokID = $keanggotaan->kelompokID;

        // jadwal misa untuk kelompok 
        $jadwalMisas = JadwalMisa::with(['jenismisa', 'kelompok'])
            ->where('kelompokID', $kelompokID)
            ->where('periodeID', $periodeAktif->periodeID)
            ->orderBy('tanggal_jam_misa', 'asc')
            ->paginate(10);

        $kelompok = Kelompok::find($kelompokID);

        return view('lihatjadwal.index', [
            'jadwalmisas' => $jadwalMisas,
            'kelompok' => $kelompok,
            'periodeAktif' => $periodeAktif
        ]);
    }
}
