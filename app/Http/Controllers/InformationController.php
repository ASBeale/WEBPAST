<?php
namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class InformationController extends Controller
{
    //crud informasi
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        $query = Information::query();

        if (request('search')) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('body', 'like', '%' . request('search') . '%')
                ->orWhere('created_at', 'like', '%' . request('search') . '%');
            });
        }

        $informations = $query->paginate(7);

        return view('informations.index', [
            'informations' => $informations,
            'search' => request('search')
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

        return view('informations.create');
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
            'title' => 'required|max:255',
            'slug' => 'unique:informations',
            'body' => 'required',
            'image' => 'image|file|max:1024|required'
        ]);

        if($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('information-images');
        }

        Information::create($validatedData);

        return redirect()->route('informations.index')->with('success', 'Informasi berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Information $information)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('informations.show', [
            'information' => $information
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Information $information)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        return view('informations.edit', [
            'information' => $information
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Information $information)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // Validasi input
        $rules = [
            'title' => 'required|max:255',
            'body' => 'required',
            'image' => 'required',
            'image' => 'image|file|max:1024'
        ];

        if($request->slug != $information->slug) {
            $rules['slug'] = 'unique:informations';
        } 

        $validatedData = $request->validate($rules);

        // ini setelah validasi
        if($request->file('image')) {
            // kalau gambar lamanya ada, hapus gambar lamanya
            if($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            //sisipkan yang baru
            $validatedData['image'] = $request->file('image')->store('information-images');
        }

        Information::where('informationID', $information->informationID)
            ->update($validatedData);

        return redirect()->route('informations.index')->with('success', 'Informasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Information $information)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }


        if($information->image) {
            Storage::delete($information->image);
        }
        $information->delete();

        return redirect()->route('informations.index')->with('success', 'Informasi berhasil dihapus');
    }

    //slug otomatis
    public function checkSlug(Request $request) 
    {
        $slug = SlugService::createSlug(Information::class, 'slug', $request->title);

        return response()->json(['slug' => $slug]);
    }
}
