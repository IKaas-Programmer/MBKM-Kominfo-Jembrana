<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Jembrana Kab</title>
    @vite('resources/css/app.css')
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
                <p class="text-sm text-slate-500">Ringkasan berkas publikasi dan aktivitas media sosial pegawai hari
                    ini.</p>
            </div>
            <div>
                <a href="{{ route('admin.task.create') }}"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-all cursor-pointer">
                    ➕ Buat Tugas Baru
                </a>
            </div>
        </div>

        <!-- Tambahkan Notifikasi Sukses di bawah Header Page -->
        @if (session('success'))
            <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid Kartu Statistik -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Card 1 -->
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Pegawai</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['total_pegawai'] }}</p>
            </div>
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
