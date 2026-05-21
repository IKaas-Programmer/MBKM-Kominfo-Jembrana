<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('link_trackings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unik')->unique(); // Token akses link transisi (contoh: CMP-HD82KA)

            //  Menghubungkan tugas langsung ke ID di tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_tugas');
            $table->text('url_target');                 // URL sosmed tujuan
            $table->boolean('url_status')->default(0);  // 0 = Belum upload, 1 = Sudah upload
            $table->enum('status_verifikasi', ['menunggu', 'acc', 'tolak'])->default('menunggu');
            $table->string('file_bukti')->nullable();   // Path screenshot di storage

            $table->softDeletes();                      // Menambahkan kolom deleted_at untuk soft delete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_trackings');
    }
};