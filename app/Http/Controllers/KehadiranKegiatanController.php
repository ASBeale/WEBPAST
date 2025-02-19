<?php
namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Models\JenisKegiatan;
use App\Models\JadwalKegiatan;
use Illuminate\Support\Carbon;
use App\Models\KehadiranKegiatan;
use Illuminate\Support\Facades\Auth;
use App\Models\KehadiranKegiatanAnggota;

class KehadiranKegiatanController extends Controller
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
        
        $query = KehadiranKegiatan::query();

        // Filter Jenis Kegiatan
        if ($request->filled('jenis_kegiatan')) {
            $query->whereHas('jadwalKegiatan.jenisKegiatan', function($q) use ($request) {
                $q->where('jenisKegiatanID', $request->jenis_kegiatan);
            });
        }

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('jadwalKegiatan', function($subQuery) use ($searchTerm) {
                    $subQuery->where('judul_kegiatan', 'like', '%' . $searchTerm . '%')
                        ->orWhere('tanggal_jam_mulai_kegiatan', 'like', '%' . $searchTerm . '%')
                        ->orWhere('tanggal_jam_selesai_kegiatan', 'like', '%' . $searchTerm . '%');
                })
                ->orWhereHas('jadwalKegiatan.jenisKegiatan', function($subQuery) use ($searchTerm) {
                    $subQuery->where('jenis_kegiatan', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        if ($request->filled('start') && $request->filled('end')) {
            $query->whereHas('jadwalKegiatan', function($q) use ($request) {
                $q->whereBetween('tanggal_jam_mulai_kegiatan', [
                    Carbon::parse($request->start)->startOfDay(), 
                    Carbon::parse($request->end)->endOfDay()
                ]);
            });
        }

        // Sort dan Pagination
        $kehadirankegiatans = $query->with([
            'jadwalKegiatan.jenisKegiatan', 
            'kehadiranKegiatanAnggota.anggota'
        ])
        ->latest()
        ->paginate(10);

        $kehadirankegiatans->appends([
            'search' => $request->search,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'start' => $request->start,
            'end' => $request->end
        ]);

        return view('kehadirankegiatan.index', [
            'kehadirankegiatans' => $kehadirankegiatans,
            'jeniskegiatans' => JenisKegiatan::all()
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

        // Ambil ID jadwal kegiatan yang sudah dipakai di kehadiran kegiatan
        $jadwalterpakai = KehadiranKegiatan::pluck('jadwalKegiatanID')->toArray();

        // Ambil jadwal kegiatan yang belum dipakai
        $jadwalkegiatans = JadwalKegiatan::whereNotIn('jadwalKegiatanID', $jadwalterpakai)
            ->with('jeniskegiatan')
            ->get();

        return view('kehadirankegiatan.create', [
            'anggotas' =>  Anggota::all(),
            'jadwalkegiatans' =>  $jadwalkegiatans
        ]);
    }

 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $validatedData = $request->validate([
            'jadwalKegiatanID' => 'required|exists:jadwal_kegiatan,jadwalKegiatanID',
            'pengisi_kehadiran_kegiatan' => 'required|string|max:255',
            'anggota_ids' => 'required|array',
            'anggota_ids.*' => 'exists:anggota,anggotaID',
        ]);

        $kehadiranKegiatan = KehadiranKegiatan::create([
            'jadwalKegiatanID' => $validatedData['jadwalKegiatanID'],
            'pengisi_kehadiran_kegiatan' => $validatedData['pengisi_kehadiran_kegiatan']
        ]);

        $kegiatanAnggotaData = [];
        foreach($validatedData['anggota_ids'] as $anggotaId) {
            $kegiatanAnggotaData[] = [
                'kehadiranKegiatanID' => $kehadiranKegiatan->kehadiranKegiatanID,
                'anggotaID' => $anggotaId,
                'status_hadir' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        KehadiranKegiatanAnggota::insert($kegiatanAnggotaData);

        return redirect('/kehadirankegiatan')->with('success', 'Kehadiran kegiatan berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KehadiranKegiatan $kehadirankegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $kehadirankegiatan->load([
            'jadwalKegiatan.jenisKegiatan', 
            'kehadiranKegiatanAnggota.anggota'
        ]);

        return view('kehadirankegiatan.show', [
            'kehadirankegiatan' => $kehadirankegiatan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KehadiranKegiatan $kehadirankegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('kehadirankegiatan.edit', [
            'kehadirankegiatan' => $kehadirankegiatan,
            'anggotas' =>  Anggota::all(),
            'jadwalkegiatans' => JadwalKegiatan::all(), 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KehadiranKegiatan $kehadirankegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $validatedData = $request->validate([
            'jadwalKegiatanID' => 'required|exists:jadwal_kegiatan,jadwalKegiatanID',
            'pengisi_kehadiran_kegiatan' => 'required|string|max:255',
            'anggota_ids' => 'required|array',
            'anggota_ids.*' => 'exists:anggota,anggotaID',
        ]);

        // Update data kehadiran kegiatan
        $kehadirankegiatan->update([
            'jadwalKegiatanID' => $validatedData['jadwalKegiatanID'],
            'pengisi_kehadiran_kegiatan' => $validatedData['pengisi_kehadiran_kegiatan']
        ]);

        // Hapus relasi lama
        KehadiranKegiatanAnggota::where('kehadiranKegiatanID', $kehadirankegiatan->kehadiranKegiatanID)->delete();

        // Tambah relasi baru
        $kegiatanAnggotaData = [];
        foreach($validatedData['anggota_ids'] as $anggotaId) {
            $kegiatanAnggotaData[] = [
                'kehadiranKegiatanID' => $kehadirankegiatan->kehadiranKegiatanID,
                'anggotaID' => $anggotaId,
                'status_hadir' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        KehadiranKegiatanAnggota::insert($kegiatanAnggotaData);

        return redirect('/kehadirankegiatan')->with('success', 'Kehadiran kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KehadiranKegiatan $kehadirankegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        
        $kehadirankegiatan->delete();
        return redirect()->route('kehadirankegiatan.index')->with('success', 'Kehadiran Kegiatan berhasil dihapus');
    }
}
