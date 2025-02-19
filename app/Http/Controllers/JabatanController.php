<?php
namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
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
        $query = Jabatan::query();

        if (request('search')) {
            $query->where('nama_jabatan', 'like', '%' . request('search') . '%');
        }

        $jabatans = $query->paginate(10);

        return view('jabatans.index', compact('jabatans'));
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
        return view('jabatans.create');
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
            'nama_jabatan' => 'required|max:255',
        ]);

        Jabatan::create($validatedData);

        return redirect()->route('jabatans.index')->with('success', 'Jabatan berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jabatan $jabatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('jabatans.edit', [
            'jabatan' => $jabatan
        ]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $rules = [
            'nama_jabatan' => 'required|max:255',
        ];

        if ($jabatan->keanggotaans()->exists()) {
            return redirect()->route('jabatans.index')
                ->with('error', 'Tidak dapat diubah! jabatan memiliki data yang berkaitan');
        }

        $validatedData = $request->validate($rules);

        Jabatan::where('jabatanID', $jabatan->jabatanID)
            ->update($validatedData);

        return redirect()->route('jabatans.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah jabatan masih digunakan dalam keanggotaan
        if ($jabatan->keanggotaans()->exists()) {
            return redirect()->route('jabatans.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }

        $jabatan->delete();
        return redirect()->route('jabatans.index')
            ->with('success', 'Jabatan berhasil dihapus');
    }

}
