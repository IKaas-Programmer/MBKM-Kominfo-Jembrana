<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LinkTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data statistik ringkas
        $stats = [
            'pegawai_pns' => User::where('role', 'pegawai')->where('status_kerja', 'PNS')->count(),
            'pegawai_non' => User::where('role', 'pegawai')->where('status_kerja', 'NON_PNS')->count(),
            'tugas_menunggu' => LinkTracking::where('status_verifikasi', 'menunggu')->count(),
        ];

        // Ekstrak total pegawai agar sinkron dengan di Blade (untuk link ke halaman pegawai)
        $totalPegawai = User::where('role', 'pegawai')->count();

        // 2. Ambil 5 riwayat pelacakan tautan terbaru beserta data pegawainya (Eager Loading)
        $recentTrackings = LinkTracking::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTrackings', 'totalPegawai'));
    }

    /**
     * Menampilkan halaman form pembuatan tugas baru.
     */
    public function createTask()
    {
        return view('admin.create-task');
    }

    /**
     * Memproses verifikasi (ACC / Tolak) bukti tugas pegawai.
     */
    public function verifikasiTask(Request $request, $id)
    {
        $request->validate([
            'aksi' => ['required', 'in:acc,tolak'],
        ]);

        $task = LinkTracking::findOrFail($id);
        // Jika admin menolak, kita bisa mengosongkan status unggahan agar pegawai bisa kirim ulang
        $urlStatus = ($request->aksi === 'tolak') ? 0 : 1;

        $task->update([
            'status_verifikasi' => $request->aksi,
            'url_status' => $urlStatus
        ]);

        $pesan = $request->aksi === 'acc'
            ? 'Tugas berhasil disetujui (ACC)!'
            : 'Tugas telah ditolak. Pegawai diminta mengunggah ulang bukti.';

        return back()->with('success', $pesan);
    }

    /**
     * Memproses pembuatan tugas secara massal ke akun pegawai.
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'nama_tugas' => ['required', 'string', 'max:255'],
            'url_target' => ['required', 'url'],
            'deadline' => ['required', 'date', 'after:now'],
            'sasaran_kerja' => ['required', 'in:SEMUA,PNS,NON_PNS'],
        ]);

        //  Filter pegawai berdasarkan sasaran yang dipilih admin
        $queryPegawai = User::where('role', 'pegawai');

        if ($request->sasaran_kerja !== 'SEMUA') {
            $queryPegawai->where('status_kerja', $request->sasaran_kerja);
        }

        $pegawais = $queryPegawai->get();

        if ($pegawais->isEmpty()) {
            return back()->withErrors(['sasaran_kerja' => 'Tidak ditemukan pegawai dengan kategori status kerja tersebut.']);
        }

        //  Gunakan Database Transaction & Bulk Insert untuk performa tinggi
        DB::transaction(function () use ($pegawais, $request) {
            $dataInsert = [];
            $now = now();

            foreach ($pegawais as $pegawai) {
                $kodeUnik = 'JMB-' . strtoupper(Str::random(5));

                // Pengecekan keunikan kode tetap dipertahankan
                while (LinkTracking::where('kode_unik', $kodeUnik)->exists()) {
                    $kodeUnik = 'JMB-' . strtoupper(Str::random(5));
                }

                $dataInsert[] = [
                    'user_id' => $pegawai->id,
                    'kode_unik' => $kodeUnik,
                    'nama_tugas' => $request->nama_tugas,
                    'url_target' => $request->url_target,
                    'deadline' => $request->deadline,
                    'url_status' => 0,
                    'status_verifikasi' => 'menunggu',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Eksekusi hanya dengan 1 query massal ke database
            LinkTracking::insert($dataInsert);
        });

        return redirect()->route('admin.dashboard')->with('success', 'Tugas publikasi berhasil disebarkan ke ' . $pegawais->count() . ' pegawai!');
    }
}