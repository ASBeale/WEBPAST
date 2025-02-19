<?php
namespace App\Http\Controllers;

use App\Models\Information;

class InformationsController extends Controller
{
    //untuk menampilkan informasi pada halaman utama home
    public function index() {
        return view('informations', [
            'title' => 'Informasi', 
            'informations' => Information::all()
        ]);
    }

    public function show (Information $information) {
        return view('information', [
            'title' => 'Informasi', 
            'information' => $information
        ]);
    }
}
