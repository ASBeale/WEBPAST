<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    // index1 metode yang dipanggil untuk website halaman utama contact 
    public function index1() {
        return view('contact', [
            'title' => 'Contact',
            'contact' => Contact::all()
        ]);
    }

    //crud contact
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        $query = Contact::query();

        if (request('search')) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('isi', 'like', '%' . request('search') . '%')
                ->orWhere('created_at', 'like', '%' . request('search') . '%');
            });
        }

        $contacts = $query->paginate(7);

        return view('contacts.index', [
            'contacts' => $contacts,
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
        return view('contacts.create');
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
            'isi' => 'required',
            'image' => 'image|file|max:1024|required'
        ]);

        if($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('contact-images');
        }

        Contact::create($validatedData);

        return redirect()->route('contacts.index')->with('success', 'Contact berhasil ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('contacts.show', [
            'contact' => $contact
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        return view('contacts.edit', [
            'contact' => $contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        // Validasi input
        $rules = [
            'title' => 'required|max:255',
            'isi' => 'required',
            'image' => 'image|file|max:1024'
        ];

        $validatedData = $request->validate($rules);

        // ini setelah validasi
        if($request->file('image')) {
            // kalau gambar lamanya ada, hapus gambar lamanya
            if($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            //sisipkan yang baru
            $validatedData['image'] = $request->file('image')->store('contact-images');
        }
        
        Contact::where('contactID', $contact->contactID)
            ->update($validatedData);

        return redirect()->route('contacts.index')->with('success', 'Contact berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }
        if($contact->image) {
            Storage::delete($contact->image);
        }
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact berhasil dihapus');
    }
}
