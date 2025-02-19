<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisKegiatan;
use App\Models\JadwalKegiatan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class JadwalKegiatanController extends Controller
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
        
        $query = JadwalKegiatan::query();

        // Filter Jenis Kegiatan
        if ($request->filled('jenis_kegiatan')) {
            $query->whereHas('jeniskegiatan', function($q) use ($request) {
                $q->where('jenisKegiatanID', $request->jenis_kegiatan);
            });
        }

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul_kegiatan', 'like', '%' . $searchTerm . '%')
                ->orWhere('tanggal_jam_mulai_kegiatan', 'like', '%' . $searchTerm . '%')
                ->orWhere('tanggal_jam_selesai_kegiatan', 'like', '%' . $searchTerm . '%')
                ->orWhereHas('jeniskegiatan', function($subQuery) use ($searchTerm) {
                    $subQuery->where('jenis_kegiatan', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Filter Rentang Tanggal
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('tanggal_jam_mulai_kegiatan', [
                Carbon::parse($request->start)->startOfDay(), 
                Carbon::parse($request->end)->endOfDay()
            ]);
        }

        // Sort dan Pagination
        $jadwalkegiatans = $query->with(['jeniskegiatan'])->latest()->paginate(10);

        $jadwalkegiatans->appends([
            'search' => $request->search,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'start' => $request->start,
            'end' => $request->end
        ]);

        return view('jadwalkegiatan.index', [
            'jadwalkegiatans' => $jadwalkegiatans,
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

        return view('jadwalkegiatan.create', [
            'jadwalkegiatans' => JadwalKegiatan::all(),
            'jeniskegiatans' => JenisKegiatan::all()
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
            'judul_kegiatan' => 'required|max:255',
            'jenisKegiatanID' => 'required|exists:jenis_kegiatan,jenisKegiatanID',
            'tanggal_jam_mulai_kegiatan' => 'required|date',
            'tanggal_jam_selesai_kegiatan' => 'required|date',

        ]);

        JadwalKegiatan::create($validatedData);

        return redirect()->route('jadwalkegiatan.index')->with('success', 'Jadwal Kegiatan berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JadwalKegiatan $jadwalkegiatan)
    {      
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('jadwalkegiatan.show', [
            'jadwalkegiatan' => $jadwalkegiatan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JadwalKegiatan $jadwalkegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('jadwalkegiatan.edit', [
            'jadwalkegiatan' => $jadwalkegiatan,
            'jeniskegiatans' => JenisKegiatan::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JadwalKegiatan $jadwalkegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah jadwal kegiatan berkaitan dengan data lain
        // kalau sudah ada kehadiran kegiatan nya maka ga bisa diupdate
        if ($jadwalkegiatan->kehadiranKegiatan()->exists()) {
            return redirect()->route('jadwalkegiatan.index')
                ->with('error', 'Tidak dapat diubah! terdapat data kehadirannya');
        }
        

        $rules = [
            'judul_kegiatan' => 'required|max:255',
            'jenisKegiatanID' => 'required|exists:jenis_kegiatan,jenisKegiatanID',
            'tanggal_jam_mulai_kegiatan' => 'required|date',
            'tanggal_jam_selesai_kegiatan' => 'required|date',
        ];

        $validatedData = $request->validate($rules);

        $jadwalkegiatan->update($validatedData);

        return redirect()->route('jadwalkegiatan.index')->with('success', 'Jadwal Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JadwalKegiatan $jadwalkegiatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah jadwal kegiatan memiliki kegiatan terkait
        if ($jadwalkegiatan->kehadiranKegiatan()->exists()) {
            return redirect()->route('jadwalkegiatan.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }
        
        $jadwalkegiatan->delete();
        return redirect()->route('jadwalkegiatan.index')->with('success', 'Jadwal Kegiatan berhasil dihapus');
    }
}
