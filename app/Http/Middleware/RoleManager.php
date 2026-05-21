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

        // Cek apakah role user sesuai dengan parameter rute
        if (Auth::user()->role !== $role) {
            // Jika pegawai coba masuk ke admin, atau sebaliknya, tendang sesuai role aslinya
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect('/pegawai/dashboard');
        }

        return $next($request);
    }
}