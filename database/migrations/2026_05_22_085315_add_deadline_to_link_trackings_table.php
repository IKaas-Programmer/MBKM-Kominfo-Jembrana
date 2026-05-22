<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('link_trackings', function (Blueprint $table) {
            // Menambahkan kolom deadline dengan tipe datetime
            $table->dateTime('deadline')->nullable()->after('url_target');
        });
    }

    public function down(): void
    {
        Schema::table('link_trackings', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });
    }
};