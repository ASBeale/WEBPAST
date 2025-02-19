<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
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
        if ($request->has('roleID')) {
            $role = $request->input('roleID');
            
            // Hanya filter jika roleID adalah 0 atau 1
            if (in_array($role, ['1','2','3','4',])) {
                $query->where('roleID', $role);
            }
        }

        // search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%');
            });        
        }

        $anggotas = $query->latest()->paginate(15);

        $anggotas->appends([
            'search' => $request->search,
            'roleID' => $request->roleID
        ]);

        return view('role.index', [
            'anggotas' => $anggotas,
            'role' => Role::all(),
            'search' => $request->search,
            'roleID' => $request->roleID
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($anggota)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $ang = Anggota::where('anggotaID', $anggota)->first();

        return view('role.show', [
            'anggota' => $ang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($anggota)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $ang = Anggota::where('anggotaID', $anggota)->first();

        return view('role.edit', [
            'anggota' => $ang,
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $anggota)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        // beri larangan kalau admin tidak bisa ubah role dirinya sendiri, karena kalau tidak sengaja keubah kehilangan akses sebagai admin 
        if (Auth::id() == $anggota) {
            return redirect()->route('role.index')->with('error', 'Tidak dapat mengubah role akun sendiri.');
        }

        // Validasi data
        $validatedData = $request->validate([
            'roleID' => 'required|exists:role,roleID'
        ]);

        $perbarui = Anggota::where('anggotaID', $anggota)->first();

        $perbarui->update([
            'roleID' => $validatedData['roleID']
        ]);

        return redirect()->route('role.index')->with('success', 'Role anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
