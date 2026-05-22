<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Jembrana Kab</title>

    <!-- Memuat aset utama global via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Wadah untuk CSS tambahan spesifik dari halaman tertentu -->
    @stack('styles')
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR NAVIGASI -->
        <aside class="w-64 border-r border-slate-200 bg-white flex-col justify-between hidden md:flex">
            <div class="p-6 space-y-6">
                <!-- Logo / Nama Sistem -->
                <div class="flex items-center gap-2">
                    <span
                        class="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">JK</span>
                    <span class="font-bold tracking-tight text-slate-900">Jembrana Kab</span>
                </div>

                <!-- Menu Navigasi -->
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.pegawai.index') }}"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.pegawai.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Data Pegawai</span>
                    </a>

                    <a href="{{ route('admin.postingan.index') }}"
                        class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.postingan.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span>Data Postingan</span>
                    </a>
                </nav>
            </div>

            <!-- Identitas Admin Aktif di Bagian Bawah Sidebar -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div
                        class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center font-semibold text-slate-700 uppercase">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold text-slate-900 truncate">
                            {{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-slate-400 truncate">Panel Kontrol Utama</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KONTEN UTAMA RIGHT SIDE -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header Navbar -->
            <header class="h-16 border-b border-slate-200 bg-white flex items-center justify-between px-6">
                <!-- Tombol Menu Mobile (Akan muncul di layar kecil) -->
                <button class="md:hidden p-1 rounded-lg text-slate-500 hover:bg-slate-100">
                    Menu
                </button>

                <div class="ml-auto flex items-center gap-4">
                    <!-- Form Logout Standar Laravel -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="text-xs font-medium text-rose-600 hover:text-rose-700 hover:underline">
                            Keluar Sistem
                        </button>
                    </form>
                </div>
            </header>

            <!-- Area Wadah Konten Dinamis -->
            <main class="flex-1 overflow-y-auto bg-slate-50 p-4 sm:p-6 lg:p-8">
                @yield('content') {{-- Konten halaman anak akan disuntikkan di sini --}}
            </main>
        </div>

    </div>

    <!-- Wadah untuk JavaScript tambahan spesifik dari halaman tertentu -->
    @stack('scripts')
</body>

</html>
