<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jembrana Kab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth/login-toggle.js'])
</head>

<body class="bg-slate-100 flex min-h-screen items-center justify-center p-4 sm:p-6">

    <!-- Card Utama Berukuran Besar (Split Layout) -->
    <div
        class="w-full max-w-5xl rounded-2xl bg-white shadow-xl border border-slate-200 overflow-hidden grid md:grid-cols-2 min-h-125">

        <!-- SISI KIRI: Area Visual (Otomatis tersembunyi di HP, muncul di layar md ke atas) -->
        <div
            class="hidden md:flex flex-col justify-between bg-linear-to-br from-blue-600 to-indigo-800 p-10 text-white relative overflow-hidden">
            <!-- Ornamen Gradien Latar Belakang -->
            <div
                class="absolute inset-0 opacity-15 bg-[radial-gradient(circle_at_top_right,var(--tw-gradient-stops))] from-white via-slate-900 to-slate-900">
            </div>

            <!-- Header Sisi Kiri -->
            <div class="relative z-10">
                <div class="flex items-center gap-2 font-bold text-sm tracking-wider uppercase opacity-90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    Jembrana Kab v2
                </div>
            </div>

            <!-- Konten Tengah (Bisa Anda ganti atau sisipkan tag <img> di sini) -->
            <div class="relative z-10 my-auto space-y-3">
                <h2 class="text-3xl font-extrabold leading-tight tracking-tight">Sistem Monitoring Publikasi Pegawai
                </h2>
                <p class="text-blue-100 text-sm leading-relaxed opacity-85">
                    Pantau partisipasi, kelola tautan pelacakan, dan verifikasi bukti publikasi media sosial ASN dalam
                    satu platform terintegrasi.
                </p>
            </div>

            <!-- Footer Sisi Kiri -->
            <div class="relative z-10 text-xs text-blue-200/80 font-mono">
                &copy; {{ date('Y') }} Pemkab Jembrana.
            </div>
        </div>

        <!-- SISI KANAN: Form Login -->
        <div class="flex flex-col justify-center p-8 sm:p-12 bg-white">
            <div class="w-full max-w-sm mx-auto">

                <!-- Header Form (Muncul penanda teks hanya saat di layar HP) -->
                <div class="mb-6">
                    <div
                        class="md:hidden flex items-center gap-1.5 font-bold text-blue-600 uppercase text-xs tracking-wider mb-2">
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-600"></span> Jembrana Kab v2
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang</h3>
                    <p class="mt-1.5 text-sm text-slate-500">Silakan masuk menggunakan akun official Anda.</p>
                </div>

                <!-- Alert Error jika Login Gagal -->
                @if ($errors->any())
                    <div class="mb-5 rounded-lg bg-red-50 p-4 text-sm text-red-600 border border-red-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form Login -->
                <form action="{{ url('/login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label for="email"
                            class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wider">Email
                            Official</label>
                        <input type="email" name="email" id="email" required value="{{ old('email') }}"
                            placeholder="nama@jembranakab.go.id"
                            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all text-slate-900 bg-slate-50/50">
                    </div>

                    <!-- Input Password -->
                    <div>
                        <label for="password"
                            class="block text-xs font-semibold text-slate-700 mb-1.5 uppercase tracking-wider">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                class="w-full rounded-lg border border-slate-300 pl-3.5 pr-10 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all text-slate-900 bg-slate-50/50">

                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg id="eyeOpenIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg id="eyeCloseIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21M17.94 17.94A10.07 10.07 0 0021.542 12c-1.274-4.057-5.064-7-9.542-7-1.157 0-2.278.196-3.32.557m0 0L12 8.5M12 12v.01">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all cursor-pointer text-center">
                            Masuk ke Aplikasi
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</body>

</html>
