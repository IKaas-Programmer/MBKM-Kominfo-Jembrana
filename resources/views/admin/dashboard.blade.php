<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Jembrana Kab</title>
    @vite('resources/css/app.css', 'resources/js/app.js')
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased">

    <!-- Top Navigation Bar -->
    <header
        class="sticky top-0 z-40 flex h-16 w-full items-center justify-between border-b border-slate-200 bg-white px-6 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="text-xl font-bold tracking-tight text-blue-600">Jembrana Kab <span
                    class="text-slate-400 font-normal">v2</span></span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-slate-600">Halo, {{ auth()->user()->name }} (Admin)</span>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition-all cursor-pointer">
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8 space-y-8">

        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Pemantauan</h1>
                <p class="text-sm text-slate-500">Ringkasan berkas publikasi dan aktivitas media sosial pegawai.</p>
            </div>
            <div>
                <a href="{{ route('admin.task.create') }}"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-all cursor-pointer">
                    Buat Tugas Baru
                </a>
            </div>
        </div>

        <!-- Notifikasi Sukses dengan ID untuk Auto-Hide -->
        @if (session('success'))
            <div id="success-alert"
                class="transition-all duration-500 ease-in-out opacity-100 max-h-40 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700 shadow-sm mb-6">
                <div class="flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <!-- Tombol Close Manual -->
                    <button onclick="dismissAlert()"
                        class="text-emerald-500 hover:text-emerald-700 font-bold ml-3 cursor-pointer">×</button>
                </div>
            </div>
        @endif

        <!-- Grid Kartu Statistik -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

            <!-- Card Total Pegawai -->
            <a href="{{ route('admin.pegawai.index') }}"
                class="group block rounded-xl border border-slate-200 bg-white p-6 shadow-xs transition-all duration-200 hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-slate-500 group-hover:text-slate-600">Total Pegawai</p>
                        <!-- Tampilkan data count dinamis dari controller -->
                        <h4 class="text-3xl font-bold tracking-tight text-slate-900">{{ $totalPegawai ?? 0 }}</h4>
                    </div>

                    <!-- Icon Kontainer -->
                    <div class="rounded-lg bg-blue-50 p-3 text-blue-600 transition-colors group-hover:bg-blue-100">
                        <!-- Contoh Icon SVG Pegawai / Users -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                </div>

                <!-- Indikator Tautan Tambahan (Opsional untuk UX yang lebih baik) -->
                <div
                    class="mt-4 flex items-center gap-1 text-xs font-medium text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <span>Lihat semua data pegawai</span>
                    <svg class="h-3.5 w-3.5 transform translate-x-0 group-hover:translate-x-0.5 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7M21 12H3" />
                    </svg>
                </div>
            </a>

            <!-- Card 2 -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pegawai PNS</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ $stats['pegawai_pns'] }}</p>
            </div>

            <!-- Card 3 -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pegawai Non-PNS</p>
                <p class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['pegawai_non'] }}</p>
            </div>

            <!-- Card 4 -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                <p class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['tugas_menunggu'] }}</p>
            </div>

        </div>

        <!-- Tabel Aktivitas Terbaru -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-xs overflow-hidden">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="font-semibold text-slate-900">Aktivitas Tautan Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase">
                            <th class="px-6 py-3">Pegawai / NIP</th>
                            <th class="px-6 py-3">Nama Tugas</th>
                            <th class="px-6 py-3">Kode Unik</th>
                            <th class="px-6 py-3">Status Verifikasi</th>
                            <th class="px-6 py-3">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTrackings as $tracking)
                            <tr class="hover:bg-slate-50/50 transition-all">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $tracking->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $tracking->user->nip }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
                                    <div>{{ $tracking->nama_tugas }}</div>
                                    <!-- Tautan untuk mengecek link sosmed target yang asli -->
                                    <a href="{{ $tracking->url_target }}" target="_blank"
                                        class="text-xs text-blue-500 hover:underline">Buka Link Target →</a>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $tracking->kode_unik }}</td>
                                <td class="px-6 py-4">
                                    @if ($tracking->status_verifikasi === 'menunggu' && $tracking->url_status == 1)
                                        <span
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 border border-blue-200">Menunggu
                                            Verifikasi</span>
                                    @elseif($tracking->status_verifikasi === 'acc')
                                        <span
                                            class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">Disetujui</span>
                                    @elseif($tracking->status_verifikasi === 'tolak')
                                        <span
                                            class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 border border-red-200">Ditolak</span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-500 border border-slate-200">Belum
                                            Dikerjakan</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    @if ($tracking->file_bukti)
                                        <div class="font-medium text-slate-900">
                                            {{ $tracking->updated_at->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            Pukul {{ $tracking->updated_at->format('H:i') }} WITA
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum mengunggah</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right space-y-2">
                                    <!-- Cek Apakah Pegawai Sudah Mengunggah Bukti -->
                                    @if ($tracking->file_bukti)
                                        <div class="flex flex-col items-end gap-1.5">
                                            <!-- Tombol Lihat Gambar Bukti -->
                                            <a href="{{ asset('storage/' . $tracking->file_bukti) }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2.5 py-1 rounded-md transition-all">
                                                👁️ Lihat Bukti
                                            </a>

                                            <!-- Tombol Aksi Persetujuan Cepat (Hanya muncul jika status belum disetujui secara permanen) -->
                                            @if ($tracking->status_verifikasi !== 'acc')
                                                <div class="flex items-center gap-1">
                                                    <!-- Form ACC -->
                                                    <form action="{{ route('admin.task.verifikasi', $tracking->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="action" value="acc">
                                                        <!-- Penyesuaian nama field request -->
                                                        <button type="submit" name="aksi" value="acc"
                                                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-2 py-1 rounded-md transition-all cursor-pointer shadow-xs">
                                                            ACC
                                                        </button>
                                                    </form>

                                                    <!-- Form Tolak -->
                                                    @if ($tracking->status_verifikasi !== 'tolak')
                                                        <form
                                                            action="{{ route('admin.task.verifikasi', $tracking->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" name="aksi" value="tolak"
                                                                class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold px-2 py-1 rounded-md transition-all cursor-pointer shadow-xs">
                                                                Tolak
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Menunggu aksi pegawai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-400">Belum ada
                                    aktivitas tugas pelacakan saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>

</html>
