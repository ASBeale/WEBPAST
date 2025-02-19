<?php
namespace App\Http\Controllers;

use App\Models\JenisMisa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisMisaController extends Controller
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
        $query = JenisMisa::query();

        if (request('search')) {
            $query->where('jenis_misa', 'like', '%' . request('search') . '%');
        }

        $jenismisas = $query->paginate(10);

        return view('jenismisa.index', compact('jenismisas'));
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
        return view('jenismisa.create');
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
            'jenis_misa' => 'required|max:255',
        ]);

        JenisMisa::create($validatedData);

        return redirect()->route('jenismisa.index')->with('success', 'Jenis misa berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisMisa $jenismisa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisMisa $jenismisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('jenismisa.edit', [
            'jenismisa' => $jenismisa
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisMisa $jenismisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $rules = [
            'jenis_misa' => 'required|max:255',
        ];

        if ($jenismisa->jadwalMisas()->exists()) {
            return redirect()->route('jenismisa.index')
                ->with('error', 'Tidak dapat diubah! Jenis Misa memiliki data yang berkaitan');
        }

        $validatedData = $request->validate($rules);

        JenisMisa::where('jenisMisaID', $jenismisa->jenisMisaID)
            ->update($validatedData);

        return redirect()->route('jenismisa.index')->with('success', 'Jenis misa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisMisa $jenismisa)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Periksa apakah jenis misa masih digunakan dalam jadwal misa
        if ($jenismisa->jadwalMisas()->exists()) {
            return redirect()->route('jenismisa.index')
                ->with('error', 'Terdapat keterkaitan dengan data lain!');
        }

        $jenismisa->delete();
        return redirect()->route('jenismisa.index')
            ->with('success', 'Jenis misa berhasil dihapus');
    }
}
