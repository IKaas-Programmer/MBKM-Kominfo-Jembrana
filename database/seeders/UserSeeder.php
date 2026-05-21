<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama Kominfo Jembrana
        User::create([
            'name' => 'Admin Kominfo Jembrana',
            'nip' => '198503212010011001', // Contoh format NIP Admin
            'email' => 'admin@jembranakab.go.id',
            'password' => Hash::make('password123'), // Password untuk login
            'role' => 'admin',
            'status_kerja' => 'PNS',
        ]);

        // 2. Akun Pegawai Contoh - Status PNS
        User::create([
            'name' => 'I Putu Eka Saputra',
            'nip' => '199211052019031002',
            'email' => 'putu.eka@jembranakab.go.id',
            'password' => Hash::make('pegawai123'),
            'role' => 'pegawai',
            'status_kerja' => 'PNS',
        ]);

        // 3. Akun Pegawai Contoh - Status Non-PNS (Kontrak/Honorer)
        User::create([
            'name' => 'Ni Made Ayu Lestari',
            'nip' => '5101021406950003', // Contoh nomor identitas pegawai kontrak
            'email' => 'ayu.lestari@gmail.com',
            'password' => Hash::make('pegawai123'),
            'role' => 'pegawai',
            'status_kerja' => 'NON_PNS',
        ]);

        // 4. Akun Pegawai Tambahan - Status Non-PNS
        User::create([
            'name' => 'I Gede Agus Wijaya',
            'nip' => '5101032211970001',
            'email' => 'agus.wijaya@gmail.com',
            'password' => Hash::make('pegawai123'),
            'role' => 'pegawai',
            'status_kerja' => 'NON_PNS',
        ]);
    }
}