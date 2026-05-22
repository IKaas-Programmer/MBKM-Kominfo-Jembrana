<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('link_trackings', function (Blueprint $col) {
            // Daftarkan opsi baru 'kedaluwarsa' ke dalam enum bersama opsi lama kamu (acc, tolak, menunggu)
            $col->enum('status_verifikasi', ['menunggu', 'acc', 'tolak', 'kedaluwarsa'])
                ->default('menunggu')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('link_trackings', function (Blueprint $col) {
            $col->enum('status_verifikasi', ['menunggu', 'acc', 'tolak'])
                ->default('menunggu')
                ->change();
        });
    }
};