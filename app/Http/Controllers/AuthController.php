<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses verifikasi akun saat tombol login ditekan.
     */
    public function login(Request $request)
    {
        // Validasi input data
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Proses otentikasi login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Pengalihan cerdas berdasarkan hak akses (Role)
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/pegawai/dashboard');
        }

        // Jika verifikasi gagal, kembalikan dengan pesan galat
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak terdaftar.',
        ])->onlyInput('email');
    }

    /**
     * Memproses pembersihan sesi (Logout).
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}