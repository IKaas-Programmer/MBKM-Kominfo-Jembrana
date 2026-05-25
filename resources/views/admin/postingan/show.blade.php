@extends('layouts.admin')

@section('content')
    <main class="mx-auto max-w-6xl p-6 space-y-6">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.postingan.index') }}"
                class="text-sm font-medium text-blue-600 hover:underline inline-flex items-center gap-1">
                <span>←</span> Kembali ke Daftar Postingan
            </a>
        </div>

        @if (session('success'))
            <div
                class="rounded-lg bg-green-50 p-4 text-sm text-green-700 border border-green-100 shadow-xs flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-blue-600">Detail Monitoring Tugas</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-1">{{ $namaTugas }}</h1>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-500 min-w-200">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-700 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3.5">Nama Pegawai / NIP</th>
                            <th class="px-6 py-3.5">Kode Unik Pelacakan</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Bukti File</th>
                            <th class="px-6 py-3.5">Status Verifikasi Admin</th>
                            <th class="px-6 py-3.5 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($trackings as $track)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-slate-900 block">{{ $track->user->name }}</span>
                                    <span class="text-xs text-slate-400 font-mono block mt-0.5">
                                        {{ $track->user->nip ?? 'NIP Tidak Tersedia' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 font-mono text-xs text-slate-700">
                                    <span class="bg-slate-100 px-2 py-1 rounded border border-slate-200 select-all">
                                        {{ $track->kode_unik }}
                                    </span>
                                </td>

                                <!-- KOLOM : Status Unggahan -->
                                <td class="px-6 py-4 text-xs font-medium">
                                    @if ($track->url_status == 1)
                                        <!-- Kondisi : Pegawai sudah sukses mengunggah bukti -->
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                            Sudah Mengunggah
                                        </span>
                                    @elseif (\Carbon\Carbon::parse($track->deadline)->isPast() && $track->url_status == 0)
                                        <!-- Kondisi : Waktu habis/lewat deadline DAN belum ada bukti masuk -->
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-2.5 py-1 text-xs text-rose-800 ring-1 ring-inset ring-rose-600/20 font-bold">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                            Tidak Mengunggah
                                        </span>
                                    @else
                                        <!-- Kondisi : Belum mengunggah, tapi waktu deadline masih tersedia -->
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Belum Mengunggah
                                        </span>
                                    @endif
                                </td>

                                <!-- KOLOM : Bukti Dokumen -->
                                <td class="px-6 py-4 text-xs">
                                    @if ($track->url_status == 1 && $track->bukti_url)
                                        <a href="{{ $track->bukti_url }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-semibold hover:underline bg-blue-50/50 px-2.5 py-1 rounded-md border border-blue-100 transition-colors">
                                            Buka Tautan Bukti
                                            <span class="text-[10px]">↗</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic font-mono">- Tidak Ada Link -</span>
                                    @endif
                                </td>

                                <!-- Tombol Verifikasi Cepat (Aksi Form PATCH) -->
                                <td class="px-6 py-4 text-center text-xs">
                                    @if ($track->status_verifikasi === 'acc' || $track->status_verifikasi === 'tolak')
                                        <span
                                            class=" bg-cyan-600 hover:bg-cyan-700 text-white px-2.5 py-1 rounded font-medium shadow-xs transition-colors cursor-pointer">Selesai
                                            diperiksa</span>
                                    @elseif (
                                        $track->status_verifikasi === 'kedaluwarsa' ||
                                            (\Carbon\Carbon::parse($track->deadline)->isPast() && $track->url_status == 0))
                                        <span class="text-rose-500 font-medium font-mono">Waktu Habis</span>
                                    @elseif (($track->status_verifikasi === 'menunggu' || $track->status_verifikasi === 'pending') && $track->url_status == 1)
                                        <div class="flex items-center justify-center gap-1.5">
                                            <form action="{{ route('admin.tracking.verify', $track->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="acc">
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-2.5 py-1 rounded font-medium shadow-xs transition-colors cursor-pointer">
                                                    ACC
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.tracking.verify', $track->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="tolak">
                                                <button type="submit"
                                                    class="bg-rose-600 hover:bg-rose-700 text-white px-2.5 py-1 rounded font-medium shadow-xs transition-colors cursor-pointer">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">Belum ada aksi</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center text-xs">
                                    @if (($track->status_verifikasi === 'menunggu' || $track->status_verifikasi === 'pending') && $track->url_status == 1)
                                        <div class="flex items-center justify-center gap-1.5">
                                            <form action="{{ route('admin.tracking.verify', $track->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="acc">
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-2.5 py-1 rounded font-medium shadow-xs transition-colors cursor-pointer">
                                                    ACC
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.tracking.verify', $track->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="tolak">
                                                <button type="submit"
                                                    class="bg-rose-600 hover:bg-rose-700 text-white px-2.5 py-1 rounded font-medium shadow-xs transition-colors cursor-pointer">
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
                            <!-- Tampilan Saat Record Kosong (Colspan disesuaikan menjadi 6) -->
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic bg-slate-50/50">
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
