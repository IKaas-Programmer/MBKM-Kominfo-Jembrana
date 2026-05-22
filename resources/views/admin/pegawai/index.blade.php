<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pegawai - Jembrana Kab</title>
    {{-- Memuat aset CSS global, JS global, dan skrip pencarian otomatis khusus secara modular via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/pegawai-search.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased">

    <!-- Top Navigation -->
    <header
        class="sticky top-0 z-40 flex h-16 w-full items-center justify-between border-b border-slate-200 bg-white px-6 shadow-xs">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-blue-600 hover:underline">
                ← Kembali ke Dashboard
            </a>
        </div>
        <span class="text-sm font-semibold text-slate-500">Panel Admin</span>
    </header>

    <main class="mx-auto max-w-6xl p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Data Pegawai</h1>
                <p class="text-sm text-slate-500">Berikut adalah daftar seluruh pegawai aktif sistem Jembrana Kab.</p>
            </div>
        </div>

        <!-- Form Pencarian (Terintegrasi otomatis dengan resources/js/pegawai-search.js) -->
        <form id="searchForm" action="{{ route('admin.pegawai.index') }}" method="GET" class="mb-4 flex gap-2">
            <input id="searchInput" type="text" name="search" value="{{ $currentSearch ?? '' }}"
                placeholder="Cari nama atau NIP pegawai..."
                class="rounded-lg border border-slate-200 px-4 py-2 text-sm w-full max-w-xs focus:border-blue-500 focus:outline-hidden"
                autocomplete="off">

            <!-- Tombol cari tetap dipertahankan sebagai cadangan aksesibilitas jika JS gagal dimuat -->
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors">
                Cari
            </button>
        </form>

        <!-- Tabel Data Pegawai -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left text-sm text-slate-500">
                    <thead
                        class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-700 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Nama Pegawai</th>
                            <th scope="col" class="px-6 py-3.5">NIP</th>
                            <th scope="col" class="px-6 py-3.5">Status Kerja</th>
                            <th scope="col" class="px-6 py-3.5">Terdaftar Pada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 border-t border-slate-100">
                        @forelse($pegawais as $pegawai)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $pegawai->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-600">
                                    {{ $pegawai->nip ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ $pegawai->status_kerja ?? 'Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    {{ $pegawai->created_at->translatedFormat('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400">
                                    @if (!empty($currentSearch))
                                        Tidak ditemukan data pegawai dengan kata kunci "{{ $currentSearch }}".
                                    @else
                                        Belum ada data pegawai yang terdaftar di dalam sistem.
                                    @endif
                                </td>
                            </tr>
                        @endempty
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        @if ($pegawais->hasPages())
            <div class="border-t border-slate-200 px-6 py-4 bg-slate-50">
                {{-- Mengunci parameter pencarian agar tidak hilang sewaktu berpindah nomor halaman --}}
                {{ $pegawais->appends(['search' => $currentSearch])->links() }}
            </div>
        @endif
    </div>

</main>

</body>

</html>
