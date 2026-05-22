<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LinkTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data statistik ringkas
        $stats = [
            'total_pegawai' => User::where('role', 'pegawai')->count(),
            'pegawai_pns' => User::where('role', 'pegawai')->where('status_kerja', 'PNS')->count(),
            'pegawai_non' => User::where('role', 'pegawai')->where('status_kerja', 'NON_PNS')->count(),
            'tugas_menunggu' => LinkTracking::where('status_verifikasi', 'menunggu')->count(),
        ];

        // 2. Ambil 5 riwayat pelacakan tautan terbaru beserta data pegawainya (Eager Loading)
        $recentTrackings = LinkTracking::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTrackings'));
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

        // 1. Filter pegawai berdasarkan sasaran yang dipilih admin
        $queryPegawai = User::where('role', 'pegawai');

        if ($request->sasaran_kerja !== 'SEMUA') {
            $queryPegawai->where('status_kerja', $request->sasaran_kerja);
        }

        $pegawais = $queryPegawai->get();

        if ($pegawais->isEmpty()) {
            return back()->withErrors(['sasaran_kerja' => 'Tidak ditemukan pegawai dengan kategori status kerja tersebut.']);
        }

        // 2. Lakukan perulangan untuk membuatkan tugas unik bagi setiap pegawai
        foreach ($pegawais as $pegawai) {
            // Membuat kode unik acak sepanjang 8 karakter, contoh: JMB-X9A2B
            $kodeUnik = 'JMB-' . strtoupper(Str::random(5));

            // Pastikan kode_unik benar-benar unik di database
            while (LinkTracking::where('kode_unik', $kodeUnik)->exists()) {
                $kodeUnik = 'JMB-' . strtoupper(Str::random(5));
            }

            LinkTracking::create([
                'user_id' => $pegawai->id,
                'kode_unik' => $kodeUnik,
                'nama_tugas' => $request->nama_tugas,
                'url_target' => $request->url_target,
                'deadline' => $request->deadline,
                'url_status' => 0,
                'status_verifikasi' => 'menunggu',
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Tugas publikasi berhasil disebarkan ke ' . $pegawais->count() . ' pegawai!');
    }
}