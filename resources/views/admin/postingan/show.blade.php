<!-- resources/views/admin/postingan/show.blade.php -->
@extends('layouts.admin')

@section('content')
    <main class="mx-auto max-w-6xl p-6 space-y-6">
        <!-- Navigasi Kembali -->
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.postingan.index') }}"
                class="text-sm font-medium text-blue-600 hover:underline inline-flex items-center gap-1">
                <span>←</span> Kembali ke Daftar Postingan
            </a>
        </div>

        <!-- Header Judul -->
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600">Detail Monitoring Tugas</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">{{ $namaTugas }}</h1>
        </div>

        <!-- Kontainer Tabel Responsif -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-500 min-w-200">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-700 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Nama Pegawai / NIP</th>
                            <th class="px-6 py-3.5">Kode Unik Pelacakan</th>
                            <th class="px-6 py-3.5">Status & Bukti Dokumen</th>
                            <th class="px-6 py-3.5">Status Verifikasi Admin</th>
                            <th class="px-6 py-3.5 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($trackings as $track)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Identitas Pegawai -->
                                <td class="px-6 py-4">
                                    <span class="font-medium text-slate-900 block">{{ $track->user->name }}</span>
                                    <span class="text-xs text-slate-400 font-mono block mt-0.5">
                                        {{ $track->user->nip ?? 'NIP Tidak Tersedia' }}
                                    </span>
                                </td>

                                <!-- Kode Pelacakan -->
                                <td class="px-6 py-4 font-mono text-xs text-slate-700">
                                    <span class="bg-slate-100 px-2 py-1 rounded border border-slate-200 select-all">
                                        {{ $track->kode_unik }}
                                    </span>
                                </td>

                                <!-- Bukti Input -->
                                <td class="px-6 py-4 text-xs">
                                    <div class="space-y-1">
                                        @if ($track->url_status == 1)
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                ● Sudah Unggah Bukti
                                            </span>
                                            <!-- Menampilkan link screenshot/bukti eksternal jika ada di database -->
                                            @if ($track->bukti_url)
                                                <a href="{{ $track->bukti_url }}" target="_blank"
                                                    class="block text-blue-600 hover:underline font-medium pt-1">
                                                    Buka Link Bukti/Screenshot ↗
                                                </a>
                                            @endif
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                                ○ Belum/Gagal Unggah
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Verifikasi -->
                                <td class="px-6 py-4 text-xs">
                                    @if ($track->status_verifikasi === 'acc')
                                        <span
                                            class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            Disetujui (ACC)
                                        </span>
                                    @elseif($track->status_verifikasi === 'tolak')
                                        <span
                                            class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 font-medium text-rose-700 ring-1 ring-inset ring-rose-600/10">
                                            Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20">
                                            Menunggu Review
                                        </span>
                                    @endif
                                </td>

                                <!-- Tombol Verifikasi Cepat (Aksi Form PATCH) -->
                                <td class="px-6 py-4 text-center text-xs">
                                    @if ($track->status_verifikasi === 'pending' && $track->url_status == 1)
                                        <div class="flex items-center justify-center gap-1.5">
                                            <form action="{{ route('admin.tracking.verify', $track->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="acc">
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-2.5 py-1 rounded font-medium shadow-xs transition-colors">
                                                    ACC
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.tracking.verify', $track->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="tolak">
                                                <button type="submit"
                                                    class="bg-rose-600 hover:bg-rose-700 text-white px-2.5 py-1 rounded font-medium shadow-xs transition-colors">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">Selesai diperiksa</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <!-- Tampilan Saat Record Kosong -->
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic bg-slate-50/50">
                                    Belum ada data pelacakan pegawai untuk postingan monitoring ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
