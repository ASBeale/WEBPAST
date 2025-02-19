<?php
namespace App\Http\Controllers;

use App\Models\Keanggotaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    
    public function index() {
        return view('login.index', [
            'title' => 'Kehadiran PAST'
        ]);
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            $user = Auth::user();
    
            // Cek role yang dimiliki user
            if ($user->roleID == '1') {
                return redirect()->intended('dashboard'); // Redirect ke halaman admin
            } elseif (in_array($user->roleID, ['3', '4']) && $user->status_anggota == '1') {
                // Cek keanggotaan aktif untuk role Pengurus (3) dan Anggota (4)
                $keanggotaanAktif = Keanggotaan::where('anggotaID', $user->anggotaID)
                    ->whereHas('periode', function($query) {
                        $query->where('status_periode', true);
                    })
                    ->exists();

                if ($keanggotaanAktif) {
                    return redirect()->intended('datakelompok'); // Redirect ke halaman PiMi dan Pengurus
                } else {
                    Auth::logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();
                    return back()->with('error', 'Anda tidak memiliki keanggotaan aktif pada periode saat ini');
                }
            } else {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                return back()->with('error', 'Anda tidak memiliki akses untuk login');
            }
        }
        return back()->with('error', 'Username /  Password salah');
    }

    // Fungsi logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

}
