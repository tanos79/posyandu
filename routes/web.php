<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kader\DashboardController as KaderDashboardController;
use App\Http\Controllers\Kader\AnakController as KaderAnakController;
use App\Http\Controllers\Kader\UserController as KaderUserController;
use App\Http\Controllers\Kader\PengukuranController as KaderPengukuranController;
use App\Http\Controllers\Kader\LaporanController as KaderLaporanController;
use App\Http\Controllers\OrangTua\OrangTuaController;

// Landing Page
Route::get('/', function () {
    $is_logged_in = Auth::check();
    $role = null;
    $username = '';
    if ($is_logged_in) {
        $user = Auth::user();
        $role = $user->role;
        $username = $user->username;
    }
    return view('landing', compact('is_logged_in', 'role', 'username'));
})->name('landing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get'); // Support for legacy GET logout link

// Kader Panel
Route::group([
    'prefix' => 'kader',
    'middleware' => ['auth', 'role:kader']
], function () {
    Route::get('/dashboard', [KaderDashboardController::class, 'index'])->name('kader.dashboard');
    Route::get('/jemput-bola', [KaderDashboardController::class, 'jemputBola'])->name('kader.jemput-bola');
    Route::get('/profil', [KaderDashboardController::class, 'profil'])->name('kader.profil');
    Route::post('/profil', [KaderDashboardController::class, 'updateProfil'])->name('kader.update-profil');

    // Anak CRUD
    Route::get('/anak', [KaderAnakController::class, 'index'])->name('kader.anak.index');
    Route::get('/anak/tambah', [KaderAnakController::class, 'create'])->name('kader.anak.create');
    Route::post('/anak/tambah', [KaderAnakController::class, 'store'])->name('kader.anak.store');
    Route::get('/anak/{id}/edit', [KaderAnakController::class, 'edit'])->name('kader.anak.edit');
    Route::post('/anak/{id}/edit', [KaderAnakController::class, 'update'])->name('kader.anak.update');
    Route::get('/anak/{id}/hapus', [KaderAnakController::class, 'destroy'])->name('kader.anak.destroy'); // GET destroy for legacy support

    // User Accounts Management
    Route::get('/pengguna', [KaderUserController::class, 'index'])->name('kader.user.index');
    Route::post('/pengguna/tambah', [KaderUserController::class, 'store'])->name('kader.user.store');
    Route::get('/pengguna/{id}/hapus', [KaderUserController::class, 'destroy'])->name('kader.user.destroy');

    // Monitoring & Growth Measurements
    Route::get('/pengukuran', [KaderPengukuranController::class, 'index'])->name('kader.pengukuran.index');
    Route::get('/pengukuran/{id}/tambah', [KaderPengukuranController::class, 'create'])->name('kader.pengukuran.create');
    Route::post('/pengukuran/tambah', [KaderPengukuranController::class, 'store'])->name('kader.pengukuran.store');
    Route::get('/imunisasi/{id}/tambah', [KaderPengukuranController::class, 'createImunisasi'])->name('kader.imunisasi.create');
    Route::post('/imunisasi/tambah', [KaderPengukuranController::class, 'storeImunisasi'])->name('kader.imunisasi.store');
    Route::get('/kirim-notif/{id_anak}', [KaderPengukuranController::class, 'kirimNotif'])->name('kader.kirim-notif');

    // Reports
    Route::get('/laporan', [KaderLaporanController::class, 'index'])->name('kader.laporan.index');
    Route::get('/laporan/cetak', [KaderLaporanController::class, 'cetak'])->name('kader.laporan.cetak');
});

// Orang Tua Panel
Route::group([
    'prefix' => 'orang-tua',
    'middleware' => ['auth', 'role:orang_tua']
], function () {
    Route::get('/dashboard', [OrangTuaController::class, 'dashboard'])->name('orang-tua.dashboard');
    Route::get('/profil-anak', [OrangTuaController::class, 'profilAnak'])->name('orang-tua.profil-anak');
    Route::get('/jadwal-imunisasi', [OrangTuaController::class, 'jadwalImunisasi'])->name('orang-tua.jadwal-imunisasi');
    Route::get('/nutrisi', [OrangTuaController::class, 'nutrisi'])->name('orang-tua.nutrisi');
    Route::get('/profil-akun', [OrangTuaController::class, 'profilAkun'])->name('orang-tua.profil-akun');
    Route::post('/profil-akun', [OrangTuaController::class, 'updateProfilAkun'])->name('orang-tua.update-profil-akun');
});
