<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cek apakah role user saat ini sesuai dengan parameter yang diminta rute
        if (Auth::user()->role === $role) {
            return $next($request);
        }

        // JIKA TIDAK SESUAI (Salah Kamar):
        // Jika pegawai nekat mengakses rute admin, arahkan ke dashboard pegawai dengan aman
        if (Auth::user()->role === 'pegawai' && $role === 'admin') {
            return redirect()->route('pegawai.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        // Jika admin mengakses rute khusus pegawai (jika ada), arahkan kembali ke dashboard admin
        if (Auth::user()->role === 'admin' && $role === 'pegawai') {
            return redirect()->route('admin.dashboard');
        }

        // Jalur penyelamat terakhir jika role benar-benar tidak dikenal, kunci dengan error 403 (Buntu, anti-loop)
        abort(403, 'Unauthorized action. Role tidak valid.');
    }
}