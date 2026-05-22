<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\LinkTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PegawaiDashboardController extends Controller
{
    /**
     * Menampilkan daftar tugas milik pegawai yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Mengambil semua tugas khusus untuk user ini
        $tasks = LinkTracking::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('pegawai.dashboard', compact('tasks'));
    }

    /**
     * Memproses unggahan bukti screenshot tugas.
     */
    public function uploadBukti(Request $request, $id)
    {
        // Pastikan tugas ini benar-of-benar milik pegawai yang bersangkutan
        $task = LinkTracking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // VALIDASI KEAMANAN: Cek apakah kiriman form sudah melewati batas waktu
        if (now()->greaterThan($task->deadline)) {
            return back()->withErrors(['deadline' => 'Gagal mengunggah! Batas waktu pengerjaan tugas ini telah berakhir.']);
        }

        $request->validate([
            'file_bukti' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Maksimal 2MB
        ]);

        // Hapus bukti lama jika ada dan ingin diganti
        if ($task->file_bukti) {
            Storage::disk('public')->delete($task->file_bukti);
        }

        // Simpan file bukti baru ke folder storage/app/public/bukti_tugas
        $path = $request->file('file_bukti')->store('bukti_tugas', 'public');

        // Perbarui status data di database
        $task->update([
            'file_bukti' => $path,
            'url_status' => 1, // Mengubah status menjadi "Sudah Upload"
            'status_verifikasi' => 'menunggu', // Reset ke menunggu jika sebelumnya ditolak
        ]);

        return back()->with('success', 'Bukti tugas berhasil diunggah! Menunggu verifikasi admin.');
    }
}