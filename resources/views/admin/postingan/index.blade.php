<!-- resources/views/admin/postingan/index.blade.php -->
@extends('layouts.admin')

@section('content')
    <main class="mx-auto max-w-6xl p-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Daftar Postingan Tugas</h1>
            <p class="text-sm text-slate-500">Pilih salah satu tugas di bawah untuk memantau detail pengerjaan seluruh
                pegawai.</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
            <table class="w-full text-left text-sm text-slate-500">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Nama Tugas / Postingan</th>
                        <th class="px-6 py-3.5">Batas Batas (Deadline)</th>
                        <th class="px-6 py-3.5">Total Sasaran Pegawai</th>
                        <th class="px-6 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($postingans as $postingan)
                        <tr class="hover:bg-slate-50/70">

                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900 block">{{ $postingan->nama_tugas }}</span>
                                <!-- Perubahan: Mengubah span menjadi tag link (a) yang bisa diklik -->
                                <a href="{{ $postingan->url_target }}" target="_blank" rel="noopener noreferrer"
                                    class="text-xs text-blue-600 hover:text-blue-800 font-mono block truncate max-w-xs hover:underline"
                                    title="Buka tautan target">
                                    {{ $postingan->url_target }}
                                </a>
                            </td>

                            <td class="px-6 py-4 text-xs">
                                {{ \Carbon\Carbon::parse($postingan->deadline)->translatedFormat('d M Y, H:i') }} Wita
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                    {{ $postingan->total_sasaran }} Pegawai
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <a href="{{ route('admin.postingan.show', ['nama_tugas' => $postingan->nama_tugas]) }}"
                                    class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                    Pantau Progress →
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400">Belum ada postingan
                                tugas yang disebarkan.</td>
                        </tr>
                    @endempty
            </tbody>
        </table>
    </div>
    {{ $postingans->links() }}
</main>
@endsection
