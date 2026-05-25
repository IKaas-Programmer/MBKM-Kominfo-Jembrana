<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tautan Kedaluwarsa - Jembrana Kab</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-slate-50 font-sans antialiased flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded-2xl border border-slate-200 p-8 shadow-sm text-center space-y-6">
        <div
            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 text-amber-600 border border-amber-200">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6m16.804 0A11.959 11.959 0 0 1 20.402 6M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15h.008v.008H12V15Z" />
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl font-bold tracking-tight text-slate-900">Tautan Telah Kedaluwarsa</h1>
            <p class="text-sm text-slate-500 leading-relaxed">
                Maaf, tautan tugas publikasi ini sudah melewati batas waktu (*deadline*) yang ditentukan oleh Admin dan
                tidak dapat diakses lagi.
            </p>
        </div>

        <div class="rounded-lg bg-slate-50 p-3.5 text-xs text-left text-slate-600 border border-slate-100 space-y-1">
            <p>📌 **Nama Tugas:** <span class="font-medium text-slate-800">{{ $task->nama_tugas }}</span></p>
            <p>⏰ **Batas Waktu:** <span
                    class="font-medium text-rose-600">{{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d F Y, H:i') }}
                    WITA</span></p>
        </div>

        <div>
            <a href="{{ route('pegawai.dashboard') }}"
                class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-all cursor-pointer">
                Kembali ke Dashboard Saya
            </a>
        </div>
    </div>
</body>

</html>
