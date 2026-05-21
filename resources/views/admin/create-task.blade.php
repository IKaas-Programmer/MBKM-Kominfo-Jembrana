<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tugas Baru - Admin</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased">

    <!-- Navbar -->
    <header
        class="sticky top-0 z-40 flex h-16 w-full items-center justify-between border-b border-slate-200 bg-white px-6 shadow-xs">
        <span class="text-xl font-bold tracking-tight text-blue-600">Jembrana Kab <span
                class="text-slate-400 font-normal">v2</span></span>
        <a href="{{ route('admin.dashboard') }}"
            class="text-sm font-medium text-slate-600 hover:text-blue-600 transition-all">← Kembali ke Dashboard</a>
    </header>

    <main class="mx-auto max-w-2xl p-4 sm:p-6 lg:p-8">

        <div class="rounded-xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
            <div class="mb-6 border-b border-slate-100 pb-4">
                <h1 class="text-xl font-bold text-slate-900">Sebarkan Tugas Publikasi Baru</h1>
                <p class="text-sm text-slate-500 mt-1">Sistem akan otomatis membuat token pelacakan terpisah untuk
                    setiap pegawai target.</p>
            </div>

            <!-- Menampilkan Error Validasi Global -->
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.task.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama Tugas -->
                <div>
                    <label for="nama_tugas" class="block text-sm font-medium text-slate-700 mb-1">Nama / Deskripsi
                        Tugas</label>
                    <input type="text" name="nama_tugas" id="nama_tugas" required
                        placeholder="Contoh: Publikasi Berita Peresmian Tol Jagat Kerti Jembrana"
                        value="{{ old('nama_tugas') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all">
                </div>

                <!-- URL Target -->
                <div>
                    <label for="url_target" class="block text-sm font-medium text-slate-700 mb-1">URL / Link Sosmed
                        Target</label>
                    <input type="url" name="url_target" id="url_target" required
                        placeholder="https://www.instagram.com/p/xxxxx/" value="{{ old('url_target') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5  focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 transition-all font-mono text-xs">
                </div>

                <!-- Sasaran Distribusi Tugas -->
                <div>
                    <label for="sasaran_kerja" class="block text-sm font-medium text-slate-700 mb-2">Target Sasaran
                        Pegawai</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label
                            class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition-all">
                            <input type="radio" name="sasaran_kerja" value="SEMUA" checked
                                class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-800">Semua Pegawai</span>
                        </label>
                        <label
                            class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition-all">
                            <input type="radio" name="sasaran_kerja" value="PNS"
                                class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-800">PNS Sahaja</span>
                        </label>
                        <label
                            class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 hover:bg-slate-50 cursor-pointer transition-all">
                            <input type="radio" name="sasaran_kerja" value="NON_PNS"
                                class="text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-800">Non-PNS Kontrak</span>
                        </label>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all">Batal</a>
                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-all cursor-pointer">
                        🚀 Sebarkan Link Tugas
                    </button>
                </div>
            </form>
        </div>

    </main>

</body>

</html>
