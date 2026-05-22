<!-- resources/views/admin/postingan/show.blade.php -->
@extends('layouts.admin')

@section('content')
    <main class="mx-auto max-w-6xl p-6 space-y-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.postingan.index') }}" class="text-sm font-medium text-blue-600 hover:underline">← Kembali
                ke Daftar Postingan</a>
        </div>

        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600">Detail Monitoring Tugas</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">{{ $namaTugas }}</h1>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
            <table class="w-full text-left text-sm text-slate-500">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Nama Pegawai / NIP</th>
                        <th class="px-6 py-3.5">Kode Unik Pelacakan</th>
                        <th class="px-6 py-3.5">Status Input Bukti</th>
                        <th class="px-6 py-3.5">Status Verifikasi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($trackings as $track)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900 block">{{ $track->user->name }}</span>
                                <span
                                    class="text-xs text-slate-400 font-mono block">{{ $track->user->nip ?? 'NIP Tidak Tersedia' }}</span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-700">
                                <span
                                    class="bg-slate-100 px-2 py-1 rounded border border-slate-200">{{ $track->kode_unik }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if ($track->url_status == 1)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        ● Sudah Unggah Bukti
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                        ○ Belum/Gagal Unggah
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if ($track->status_verifikasi === 'acc')
                                    <span
                                        class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Disetujui
                                        (ACC)</span>
                                @elseif($track->status_verifikasi === 'tolak')
                                    <span
                                        class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 font-medium text-rose-700 ring-1 ring-inset ring-rose-600/10">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">Menunggu
                                        Review</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection
