<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan menggunakan model User Anda
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        //  query dasar untuk mengambil user ber-role pegawai
        $query = User::where('role', 'pegawai');

        //  Request untuk mengecek apakah Admin sedang mengetik di kolom pencarian
        if ($request->has('search') && $request->search != '') {
            $keyword = $request->search;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('nip', 'like', '%' . $keyword . '%'); // Cari berdasarkan nama atau NIP
            });
        }

        // Eksekusi query dengan urutan nama teratur dan batasi 10 data per halaman
        $pegawais = $query->orderBy('name', 'asc')->paginate(10);

        // Kirim juga kata kunci pencarian ke view agar input pencarian tidak kosong setelah halaman direfresh
        return view('admin.pegawai.index', compact('pegawais'))->with('currentSearch', $request->search);
    }
}