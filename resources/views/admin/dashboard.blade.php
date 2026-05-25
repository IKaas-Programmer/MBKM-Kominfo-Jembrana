<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Jembrana Kab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/'])
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

    <main class="mx-auto max-w-Full p-4 sm:p-6 lg:p-8 space-y-8">

        <!-- Header Page -->
        <div class="space-y-1">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Pemantauan</h1>
            <p class="text-sm text-slate-500">Ringkasan berkas publikasi dan aktivitas media sosial pegawai.</p>
        </div>

        <!-- Notifikasi Sukses (Auto-Hide & Manual Close) -->
        @if (session('success'))
            <div id="success-alert"
                class="transition-all duration-500 ease-in-out opacity-100 max-h-40 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700 shadow-sm mb-6">
                <div class="flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="dismissAlert()"
                        class="text-emerald-500 hover:text-emerald-700 font-bold ml-3 cursor-pointer text-lg leading-none">×</button>
                </div>
            </div>
        @endif

        <!-- Grid Kartu Statistik -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

            <!-- Card Total Pegawai -->
            <a href="{{ route('admin.pegawai.index') }}"
                class="group block rounded-xl border border-slate-200 bg-white p-6 shadow-xs transition-all duration-200 hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 cursor-pointer">
                <div class="flex items-center justify-between">

                    <div class="space-y-2">
                        <p class="text-sm font-medium text-slate-500 group-hover:text-slate-600">Total Pegawai</p>
                        <h4 class="text-3xl font-bold tracking-tight text-slate-900">{{ $totalPegawai ?? 0 }}</h4>
                    </div>

                    <div class="rounded-lg bg-blue-50 p-3 text-blue-600 transition-colors group-hover:bg-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                </div>

                <div
                    class="mt-4 flex items-center gap-1 text-xs font-medium text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <span>Lihat semua data pegawai</span>
                    <svg class="h-3.5 w-3.5 transform translate-x-0 group-hover:translate-x-0.5 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7M21 12H3" />
                    </svg>
                </div>
            </a>

            <!-- Kartu Data Postingan -->
            <a href="{{ route('admin.postingan.index') }}"
                class="group block p-6 bg-white border border-slate-200 rounded-xl shadow-xs hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">

                <!-- Bagian Atas: Judul dan Icon/Label -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-500 group-hover:text-slate-600">Data Postingan</span>
                    <div
                        class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-100 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                </div>

                <!-- Bagian Tengah: Angka Statistik -->
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-bold tracking-tight text-slate-900">
                        {{ $stats['total_postingan'] ?? 0 }}
                    </span>
                    <span class="text-xs text-slate-400">Tugas Publikasi</span>
                </div>

                <!-- Bagian Bawah: Tombol Lihat Semua (Pindah ke bawah dengan efek Hover) -->
                <div
                    class="mt-4 flex items-center gap-1 text-xs font-medium text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <span>Lihat semua data postingan</span>
                    <svg class="h-3.5 w-3.5 transform translate-x-0 group-hover:translate-x-0.5 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7M21 12H3" />
                    </svg>
                </div>
            </a>

            <!-- Card Menunggu Verifikasi -->
            <div
                class="group block p-6 bg-white border border-slate-200 rounded-xl shadow-xs hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer"">
                <p class="text-sm font-medium text-slate-500">Menunggu Verifikasi</p>
                <p class="mt-4 text-3xl font-bold tracking-tight text-blue-600">{{ $stats['tugas_menunggu'] ?? 0 }}</p>
            </div>

            <a href="{{ route('admin.task.create') }}"
                class="group block p-6 bg-white border border-slate-200 rounded-xl shadow-xs hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-slate-500 group-hover:text-slate-600">Aksi Cepat Admin</p>
                        <h4 class="text-2xl font-bold tracking-tight text-slate-900">Buat Tugas Baru</h4>
                    </div>
                    <div class="rounded-lg bg-blue-50 p-3 text-blue-600 transition-colors group-hover:bg-blue-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </div>
                </div>

                <div
                    class="mt-4 flex items-center gap-1 text-xs font-medium text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <span>Buka formulir campaign baru</span>
                    <svg class="h-3.5 w-3.5 transform translate-x-0 group-hover:translate-x-0.5 transition-transform"
                        fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7M21 12H3" />
                    </svg>
                </div>
            </a>



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
                                    <div class="font-medium text-slate-800">{{ $tracking->nama_tugas }}</div>
                                    <a href="{{ $tracking->url_target }}" target="_blank"
                                        class="text-xs text-blue-500 hover:underline inline-flex items-center gap-0.5 mt-0.5">
                                        Buka Link Target →
                                    </a>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $tracking->kode_unik }}</td>

                                <td class="px-6 py-4">
                                    @if ($tracking->status_verifikasi === 'menunggu' && $tracking->deadline <= now())
                                        <span
                                            class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 border border-red-200">
                                            ⚠️ Kedaluwarsa
                                        </span>
                                    @elseif ($tracking->status_verifikasi === 'menunggu')
                                        <span
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 border border-blue-200">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($tracking->status_verifikasi === 'acc')
                                        <span
                                            class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 border border-emerald-200">
                                            Disetujui
                                        </span>
                                    @elseif($tracking->status_verifikasi === 'tolak')
                                        <span
                                            class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 border border-rose-200">
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-500 border border-slate-200">
                                            Belum Dikerjakan
                                        </span>
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

                                <td class="px-6 py-4 text-right">
                                    @if ($tracking->file_bukti)
                                        <div class="flex flex-col items-end gap-2">
                                            <!-- Tombol Lihat Gambar Bukti -->
                                            <a href="{{ asset('storage/' . $tracking->file_bukti) }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200 px-2.5 py-1 rounded-md transition-all">
                                                Lihat Bukti
                                            </a>

                                            <!-- Tombol Verifikasi Cepat -->
                                            @if ($tracking->status_verifikasi === 'menunggu')
                                                <div class="flex items-center gap-1">
                                                    <!-- Form ACC -->
                                                    <form action="{{ route('admin.task.verifikasi', $tracking->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="aksi" value="acc">
                                                        <button type="submit"
                                                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold px-2.5 py-1 rounded-md transition-all cursor-pointer shadow-xs">
                                                            ACC
                                                        </button>
                                                    </form>

                                                    <!-- Form Tolak -->
                                                    <form action="{{ route('admin.task.verifikasi', $tracking->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="aksi" value="tolak">
                                                        <button type="submit"
                                                            class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-bold px-2.5 py-1 rounded-md transition-all cursor-pointer shadow-xs">
                                                            Tolak
                                                        </button>
                                                    </form>
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
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-400">
                                    Belum ada aktivitas tugas pelacakan saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>

</html>
