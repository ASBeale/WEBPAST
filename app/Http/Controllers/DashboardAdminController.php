<?php
namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    //untuk menampilkan jumlah-jumlah pada dashboard admin
    public function index()
    {
        // Authorization
        if (!Auth::check() || Auth::user()->roleID !== 1) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman tersebut!');
        }

        // ambil yang aktif saja
        $jumlahSemua = Anggota::where('status_anggota', 1)->count();
        $jumlahAdmin = Anggota::where('roleID', 1)->where('status_anggota', 1)->count();
        $jumlahAnggota = Anggota::where('roleID', 2)->where('status_anggota', 1)->count();
        $jumlahPengurus = Anggota::where('roleID', 3)->where('status_anggota', 1)->count();
        $jumlahPiMi = Anggota::where('roleID', 4)->where('status_anggota', 1)->count();
        
        return view('dashboard.index', [
            'jumlahSemua' =>  $jumlahSemua,
            'jumlahAnggota' => $jumlahAnggota,
            'jumlahPengurus' => $jumlahPengurus,
            'jumlahPiMi' => $jumlahPiMi,
            'jumlahAdmin' => $jumlahAdmin,
        ]);
    }
}
