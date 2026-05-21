<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    Route::get('/dashboard', function () {
        return "<h1>Halaman Dashboard Admin</h1><p>Halo, " . auth()->user()->name . "</p><form action='" . route('logout') . "' method='POST'>" . csrf_field() . "<button type='submit'>Logout</button></form>";
    })->name('dashboard');
});


// ==========================================
// Halaman Khusus PEGAWAI (Hanya bisa diakses role:pegawai)
// ==========================================
Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->as('pegawai.')->group(function () {
    Route::get('/dashboard', function () {
        return "<h1>Halaman Dashboard Pegawai</h1><p>Halo, " . auth()->user()->name . "</p><form action='" . route('logout') . "' method='POST'>" . csrf_field() . "<button type='submit'>Logout</button></form>";
    })->name('dashboard');
});