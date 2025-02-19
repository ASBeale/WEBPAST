<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\JenisMisaController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\JadwalMisaController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\KeanggotaanController;
use App\Http\Controllers\InformationsController;
use App\Http\Controllers\JenisKegiatanController;
use App\Http\Controllers\KehadiranMisaController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\JadwalKegiatanController;
use App\Http\Controllers\KehadiranKegiatanController;

// route
// halaman utama
Route::get('/', [InformationsController::class, 'index']); 
Route::get('single-information/{information:slug}', [InformationsController::class, 'show']); 
Route::get('/about-PAST', [AboutController::class, 'index1']);
Route::get('/contact-PAST', [ContactController::class, 'index1']);

// login
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
// logout
Route::post('/logout', [LoginController::class, 'logout']);

// sisi Admin
Route::get('/dashboard', [DashboardAdminController::class, 'index'])->middleware('auth');
Route::resource('/anggotas', AnggotaController::class)->middleware('auth');
Route::resource('/role', RoleController::class)->middleware('auth');
Route::resource('/keanggotaan', KeanggotaanController::class)->middleware('auth');
Route::resource('/periode', PeriodeController::class)->middleware('auth');
Route::get('/keanggotaan/create/single', [KeanggotaanController::class, 'createSingle'])->middleware('auth');
Route::get('/keanggotaan/create/multi', [KeanggotaanController::class, 'createMulti'])->middleware('auth');

Route::get('/informations/checkSlug', [InformationController::class, 'checkSlug'])->middleware('auth');
Route::resource('/informations', InformationController::class)->middleware('auth');
Route::resource('/about', AboutController::class)->middleware('auth');
Route::resource('/contacts', ContactController::class)->middleware('auth');
Route::resource('/jabatans', JabatanController::class)->middleware('auth');
Route::resource('/kelompoks', KelompokController::class)->middleware('auth');
Route::resource('/jenismisa', JenisMisaController::class)->middleware('auth');
Route::resource('/jeniskegiatan', JenisKegiatanController::class)->middleware('auth');
Route::resource('/jadwalmisa', JadwalMisaController::class)->middleware('auth');
Route::resource('/jadwalkegiatan', JadwalKegiatanController::class)->middleware('auth');
Route::resource('/kehadiranmisa', KehadiranMisaController::class)->middleware('auth');

// ambil jadwal sesuai kelompok yang dipilih
Route::get('/ambiljadwal/{kelompokID}', [KehadiranMisaController::class, 'jadwalkelompok'])->middleware('auth');
Route::resource('/kehadirankegiatan', KehadiranKegiatanController::class)->middleware('auth');

//fitur rolling kelompok
Route::post('/keanggotaan/rolling', [KeanggotaanController::class, 'rollingKelompok'])->name('rolling.kelompok')->middleware(['auth']);

// sisi Pengurus
Route::get('/datakelompok', [KelompokController::class, 'datakelompok'])->middleware('auth');
Route::get('/lihatjadwal', [JadwalMisaController::class, 'LihatJadwal'])->middleware('auth');
// cru kehadiran
Route::resource('/kehadiran', KehadiranController::class)->middleware('auth');
