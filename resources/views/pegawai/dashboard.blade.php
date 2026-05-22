<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pegawai Dashboard - Jembrana Kab</title>
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
            <div class="text-right hidden sm:block">
                <div class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</div>
                <div class="text-xs text-slate-400">NIP. {{ auth()->user()->nip }} • {{ auth()->user()->status_kerja }}
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition-all cursor-pointer">
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-5xl p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Header Page -->
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Daftar Tugas Publikasi</h1>
            <p class="text-sm text-slate-500">Silakan buka tautan target sosmed, lakukan aksi, lalu unggah bukti
                tangkapan layar (screenshot).</p>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session('success'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- Daftar Kartu Tugas (Looping) -->
        <div class="space-y-4">
            @forelse($tasks as $task)
                <div
                    class="rounded-xl border border-slate-200 bg-white p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-5">

                    <!-- Sisi Kiri: Info & Status Tugas -->
                    <div class="space-y-3 max-w-xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md border border-slate-200">
                                {{ $task->kode_unik }}
                            </span>

                            <!-- Pengaman optional() jika deadline di database kosong/null -->
                            @if ($task->deadline)
                                <span class="text-xs text-red-500 font-medium">
                                    ⏳ Batas: {{ optional($task->deadline)->translatedFormat('d M Y, H:i') }} WITA
                                </span>
                            @endif

                            @if ($task->status_verifikasi === 'acc')
                                <span
                                    class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 border border-emerald-100">Disetujui
                                    Admin</span>
                            @elseif($task->deadline && !$task->file_bukti && now()->greaterThan($task->deadline))
                                <span
                                    class="inline-flex items-center rounded-md bg-rose-100 px-2 py-0.5 text-xs font-semibold text-rose-700 border border-rose-200">❌
                                    Melewati Tenggat (Tidak Mengisi)</span>
                            @elseif($task->status_verifikasi === 'menunggu' && $task->url_status == 1)
                                <span
                                    class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 border border-blue-100">Menunggu
                                    Verifikasi</span>
                            @elseif($task->status_verifikasi === 'tolak')
                                <span
                                    class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 border border-red-100">Ditolak
                                    (Mohon Upload Ulang)
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-md bg-slate-50 px-2 py-0.5 text-xs font-medium text-slate-500 border border-slate-200">Belum
                                    Dikerjakan</span>
                            @endif
                        </div>

                        <h3 class="text-base font-semibold text-slate-900">{{ $task->nama_tugas }}</h3>

                        <div class="pt-1">
                            <a href="{{ $task->url_target }}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 break-all bg-blue-50/50 hover:bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-all">
                                🌐 Buka Link Sosmed Target
                            </a>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Form Kontrol Upload Bukti -->
                    <div
                        class="w-full md:w-auto shrink-0 border-t border-dashed border-slate-200 pt-4 md:border-0 md:pt-0">
                        @if ($task->status_verifikasi === 'acc')
                            <div
                                class="flex items-center gap-2 text-sm font-medium text-emerald-600 bg-emerald-50 border border-emerald-100 px-4 py-2 rounded-lg">
                                <span>✅ Tugas Selesai Terverifikasi</span>
                            </div>
                        @elseif($task->deadline && !$task->file_bukti && now()->greaterThan($task->deadline))
                            <div
                                class="text-sm font-medium text-rose-600 bg-rose-50 border border-rose-100 px-4 py-2 rounded-lg text-center">
                                🔒 Pengunggahan Ditutup
                            </div>
                        @else
                            <form action="{{ route('pegawai.task.upload', $task->id) }}" method="POST"
                                enctype="multipart/form-data"
                                class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                @csrf
                                <div class="relative">
                                    <input type="file" name="file_bukti" required id="file_{{ $task->id }}"
                                        class="hidden" accept="image/*"
                                        onchange="document.getElementById('label_{{ $task->id }}').innerText = this.files[0].name">
                                    <label id="label_{{ $task->id }}" for="file_{{ $task->id }}"
                                        class="w-full sm:w-56 block text-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 shadow-xs cursor-pointer truncate">
                                        {{ $task->file_bukti ? '🔄 Ganti Screenshot' : '📁 Pilih Screenshot' }}
                                    </label>
                                </div>
                                <button type="submit"
                                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 shadow-sm transition-all cursor-pointer">
                                    Kirim Bukti
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @empty
                <div class="rounded-xl border border-slate-200 bg-white p-12 text-center text-sm text-slate-400">
                    Belum ada tugas pelacakan publikasi yang dibagikan untuk Anda saat ini.
                </div>
            @endforelse
        </div>

    </main>

</body>

</html>
