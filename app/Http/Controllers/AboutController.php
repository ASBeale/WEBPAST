<?php
namespace App\Http\Controllers;

use App\Models\About;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function index1() {
        return view('about', [
            'title' => 'About PAST',
            'about' => About::all()
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            abort(403);
        }

        return view('about.index', [
            'about' => About::all(),
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
    public function show(About $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(About $about)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            abort(403);
        }

        return view('about.edit', [
            'about' => $about
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            abort(403);
        }
        
        // Validasi update
        $rules = [
            'title' => 'required|max:255',
            'body' => 'required',
            'image' => 'image|file|max:1024',
        ];

        $validatedData = $request->validate($rules);

        // ini setelah validasi
        if($request->file('image')) {
            // kalau gambar lamanya ada, hapus gambar lamanya
            if($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            //sisipkan yang baru
            $validatedData['image'] = $request->file('image')->store('about-images');
        }
        
        About::where('aboutID', $about->aboutID)
            ->update($validatedData);

        return redirect()->route('about.index')->with('success', 'About berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        //
    }
}
