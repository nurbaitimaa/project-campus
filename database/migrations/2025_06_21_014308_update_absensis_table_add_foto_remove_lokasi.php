<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('absensis', function (Blueprint $table) {
            // Hapus lokasi check in/out
            $table->dropColumn(['latitude', 'longitude']);

            // Tambahkan kolom foto absensi (nullable)
            $table->string('foto')->nullable()->after('jam_keluar');
        });
    }

    public function down(): void {
        Schema::table('absensis', function (Blueprint $table) {
            // Kembalikan kolom lokasi
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // Hapus kolom foto
            $table->dropColumn('foto');
        });
    }
};
