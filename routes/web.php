<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PegawaiController;
use Illuminate\Support\Facades\Route;


// Akses Tamu/Public
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// Halaman Khusus ADMIN (Hanya bisa diakses role:admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Halaman Detail Data Pegawai
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');


    // RUTE Pembuatan Tugas
    Route::get('/task/create', [AdminDashboardController::class, 'createTask'])->name('task.create');
    Route::post('/task/store', [AdminDashboardController::class, 'storeTask'])->name('task.store');

    // RUTE Verifikasi Tugas (ACC / Tolak)
    Route::post('/task/{id}/verifikasi', [AdminDashboardController::class, 'verifikasiTask'])->name('task.verifikasi');

    // Data Postingan
    Route::get('/postingan', [AdminDashboardController::class, 'indexPostingan'])->name('postingan.index');
    Route::get('/postingan/detail', [AdminDashboardController::class, 'showPostingan'])->name('postingan.show');
});


// ==========================================
// Halaman Khusus PEGAWAI (Hanya bisa diakses role:pegawai)
// ==========================================
Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->as('pegawai.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Pegawai\PegawaiDashboardController::class, 'index'])->name('dashboard');
    Route::post('/task/{id}/upload', [\App\Http\Controllers\Pegawai\PegawaiDashboardController::class, 'uploadBukti'])->name('task.upload');
});