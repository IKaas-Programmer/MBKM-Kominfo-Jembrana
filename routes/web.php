<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
// Pastikan folder fisik file ini sesuai dengan namespace di bawah ini!
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Pegawai\PegawaiDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Akar (Dikeluarkan dari 'guest' untuk mencegah loop redirect bagi yang sudah login)
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pegawai.dashboard');
    }
    return redirect()->route('login');
});

// ==========================================
// AKSES TAMU / BELUM LOGIN (GUEST)
// ==========================================
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Akses Keluar Sistem (Wajib Login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// HALAMAN KHUSUS ADMIN (Hanya role: admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {

    // Dashboard Admin & Monitoring Utama
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen / Detail Data Pegawai
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');

    // Fitur Sebar Tautan / Broadcast (Menghubungkan Kotak 1 Dashboard ke Controller)
    Route::post('/tugas/broadcast', [AdminDashboardController::class, 'generateBroadcastLinks'])->name('tugas.broadcast');

    // RUTE Pembuatan Tugas / Campaign Baru
    Route::get('/task/create', [AdminDashboardController::class, 'createTask'])->name('task.create');
    Route::post('/task/store', [AdminDashboardController::class, 'storeTask'])->name('task.store');

    // RUTE Verifikasi & Validasi (ACC / Tolak Bukti dari Pegawai)
    Route::post('/task/{id}/verifikasi', [AdminDashboardController::class, 'verifikasiTask'])->name('task.verifikasi');
    Route::patch('/tracking/{id}/verify', [AdminDashboardController::class, 'verifyTracking'])->name('tracking.verify');

    // Data Postingan Terintegrasi
    Route::get('/postingan', [AdminDashboardController::class, 'indexPostingan'])->name('postingan.index');
    Route::get('/postingan/detail', [AdminDashboardController::class, 'showPostingan'])->name('postingan.show');
});


// ==========================================
// HALAMAN KHUSUS PEGAWAI (Hanya role: pegawai)
// ==========================================
Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->as('pegawai.')->group(function () {

    // Dashboard Utama Pegawai (Melihat Tugas Aktif)
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');

    // Upload Bukti Screenshot oleh Pegawai
    Route::post('/task/{id}/upload', [PegawaiDashboardController::class, 'uploadBukti'])->name('task.upload');
});