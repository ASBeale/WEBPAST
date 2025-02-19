<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriodeController extends Controller
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
        
        $query = Periode::query();

        // Filter
        if ($request->has('status_periode')) {
            $status = $request->input('status_periode');
            
            // Hanya filter jika status_periode adalah 0 atau 1
            if (in_array($status, ['0', '1'])) {
                $query->where('status_periode', $status);
            }
        }

        // search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_periode', 'like', '%' . $searchTerm . '%')
                ->orWhere('tanggal_mulai_periode', 'like', '%' . $searchTerm . '%')
                ->orWhere('tanggal_selesai_periode', 'like', '%' . $searchTerm . '%');
            });
        }

        $periodes = $query->latest()->paginate(5);

        $periodes->appends([
            'search' => $request->search,
            'status_periode' => $request->status_periode
        ]);

        return view('periode.index', [
            'periodes' => $periodes,
            'search' => $request->search,
            'status_periode' => $request->status_periode
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
        return view('periode.create');
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
            'nama_periode' => 'required|max:255',
            'tanggal_mulai_periode' => 'required|date',
            'tanggal_selesai_periode' => 'required|date',
        ]);

        Periode::create($validatedData);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Periode $periode)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('periode.show', [
            'periode' => $periode
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periode $periode)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('periode.edit', [
            'periode' => $periode,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Periode $periode)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Validasi data
        $validatedData = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai_periode' => 'required|date',
            'tanggal_selesai_periode' => 'required|date',
            'status_periode' => 'nullable|boolean',
        ]);

        // kalau status periode diubah jadi aktif
        if ($request->has('status_periode') && $request->status_periode == 1) {
            // non aktifkan periode lain
            Periode::where('status_periode', 1)
                ->where('periodeID', '!=', $periode->periodeID)
                ->update(['status_periode' => 0]);
        }

         // set periode yang di edit jadi aktif kalau di ceklis
        $validatedData['status_periode'] = $request->has('status_periode') ? 1 : 0;
    
        $periode->update($validatedData);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periode $periode)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah periode terkait dengan data lain
        if ($periode->keanggotaan()->exists() || $periode->jadwalMisa()->exists()) {
            return redirect()->route('periode.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }

        $periode->delete();

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus');
    }
}
