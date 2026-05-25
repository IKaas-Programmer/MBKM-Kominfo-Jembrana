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
        // // Update otomatis status tugas yang sudah melewati deadline menjadi "kedaluwarsa"
        // LinkTracking::where('status_verifikasi', 'menunggu')
        //     ->where('deadline', '<=', now())
        //     ->update(['status_verifikasi' => 'kedaluwarsa']);

        // Ekstrak total pegawai agar sinkron dengan di Blade (untuk link ke halaman pegawai)
        $totalPegawai = User::where('role', 'pegawai')->count();
        $pegawaiNon = User::where('role', 'pegawai')->where('status_kerja', 'NON_PNS')->count();

        // HITUNG DATA POSTINGAN (TUGAS UNIK)
        $totalPostingan = LinkTracking::distinct('nama_tugas')
            // ->where('deadline', '>', now()) 
            ->count('nama_tugas');

        $waitingVerification = LinkTracking::where('status_verifikasi', 'menunggu')
            ->where('deadline', '>', now()) // Menyaring agar yang sudah lewat masa tenggat tidak dihitung
            ->count();

        //  Ambil 10 riwayat pelacakan tautan terbaru beserta data pegawainya (Eager Loading)
        $recentTrackings = LinkTracking::with('user')
            ->where('status_verifikasi', '!=', 'kedaluwarsa')
            ->latest()
            ->limit(10)
            ->get();

        //  Ambil data statistik ringkas
        $stats = [
            'pegawai_non' => $pegawaiNon,
            'tugas_menunggu' => $waitingVerification,
            'total_postingan' => $totalPostingan, // Simpan ke dalam array statistik
        ];


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

        $urlTarget = rtrim(trim($request->url_target), '/');
        // Ini akan mengubah " https://google.com/ " menjadi "https://google.com"

        //  Filter pegawai berdasarkan sasaran yang dipilih admin
        $queryPegawai = User::where('role', 'pegawai');

        if ($request->sasaran_kerja !== 'SEMUA') {
            $queryPegawai->where('status_kerja', $request->sasaran_kerja);
        }

        $pegawais = $queryPegawai->get();

        if ($pegawais->isEmpty()) {
            return back()->withErrors(['sasaran_kerja' => 'Tidak ditemukan pegawai dengan kategori status kerja tersebut.']);
        }

        //  Tarik semua kode unik yang ada saat ini ke dalam memori Array sekali saja
        $existingCodes = array_flip(LinkTracking::pluck('kode_unik')->toArray());

        //  Gunakan Database Transaction & Bulk Insert untuk performa tinggi
        DB::transaction(function () use ($pegawais, $request, $urlTarget) {
            $dataInsert = [];
            $now = now();

            foreach ($pegawais as $pegawai) {
                // Pengecekan keunikan kode memanfaatkan array di memori 
                do {
                    $kodeUnik = 'JMB-' . strtoupper(Str::random(5));
                } while (isset($existingCodes[$kodeUnik]));

                // Catat kode baru agar iterasi pegawai selanjutnya tidak duplikat
                $existingCodes[$kodeUnik] = true;

                $dataInsert[] = [
                    'user_id' => $pegawai->id,
                    'kode_unik' => $kodeUnik,
                    'nama_tugas' => $request->nama_tugas,
                    'url_target' => $urlTarget,
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

    /**
     * Menampilkan semua daftar tugas publikasi unik (Daftar Postingan).
     */
    public function indexPostingan()
    {
        // Mengelompokkan HANYA berdasarkan nama_tugas agar jumlahnya akurat
        $postingans = LinkTracking::select(
            'nama_tugas',
            DB::raw('MAX(url_target) as url_target'),
            DB::raw('MAX(deadline) as deadline'),
            DB::raw('MAX(created_at) as terakhir_dibuat'),
            DB::raw('COUNT(id) as total_sasaran')
        )
            ->groupBy('nama_tugas')
            ->orderBy('terakhir_dibuat', 'desc')
            ->paginate(10);                         // Menangani pagination {{ $postingans->links() }} di Blade

        return view('admin.postingan.index', compact('postingans'));
    }

    /**
     * Menampilkan detail semua pegawai yang menerima tugas spesifik ini beserta statusnya.
     */
    public function showPostingan(Request $request)
    {
        $request->validate([
            'nama_tugas' => 'required|string',
        ]);

        $namaTugas = $request->nama_tugas;

        // Ambil semua record pegawai yang ditugaskan pada postingan ini
        $trackings = LinkTracking::with('user')
            ->where('nama_tugas', $namaTugas)
            ->get();

        return view('admin.postingan.show', compact('trackings', 'namaTugas'));
    }

    /**
     * Memproses verifikasi cepat (ACC / Tolak) dari halaman detail postingan.
     * (Sinkron dengan rute: admin.tracking.verify dan Model: LinkTracking)
     */
    public function verifyTracking(Request $request, $id)
    {
        // 1. Validasi input status yang dikirim oleh form di show.blade.php
        $request->validate([
            'status' => ['required', 'in:acc,tolak'],
        ]);

        // 2. Cari data pelacakan berdasarkan ID dengan Model LinkTracking
        $track = LinkTracking::findOrFail($id);

        // 3. Jika ditolak, ubah url_status menjadi 0 agar pegawai bisa upload ulang bukti
        $urlStatus = ($request->status === 'tolak') ? 0 : 1;

        // 4. Update data ke database
        $track->update([
            'status_verifikasi' => $request->status,
            'url_status' => $urlStatus
        ]);

        // 5. Berikan feedback pesan sukses sesuai tindakan
        $pesan = $request->status === 'acc'
            ? 'Bukti publikasi pegawai berhasil disetujui (ACC)!'
            : 'Bukti publikasi ditolak. Pegawai diminta untuk mengunggah ulang.';

        return back()->with('success', $pesan);
    }
}