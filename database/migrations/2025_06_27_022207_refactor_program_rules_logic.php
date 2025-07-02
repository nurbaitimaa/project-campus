<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus kolom aturan yang tidak perlu dari tabel 'programs'
        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'min_pembelian')) {
                $table->dropColumn(['min_pembelian', 'reward', 'reward_type']);
            }
        });

        // 2. Hapus kolom aturan lama yang berlebihan dari 'program_berjalan'
        Schema::table('program_berjalan', function (Blueprint $table) {
            if (Schema::hasColumn('program_berjalan', 'nilai_klaim_per_item')) {
                $table->dropColumn(['nilai_klaim_per_item', 'persen_klaim', 'nominal_klaim']);
            }
        });
    }

    public function down(): void
    {
        // Logika untuk mengembalikan jika perlu (rollback)
        Schema::table('programs', function (Blueprint $table) {
            $table->decimal('min_pembelian', 15, 2)->nullable();
            $table->decimal('reward', 15, 2)->nullable();
            $table->enum('reward_type', ['unit', 'rupiah', 'persen'])->nullable();
        });

        Schema::table('program_berjalan', function (Blueprint $table) {
            $table->decimal('nilai_klaim_per_item', 15, 2)->nullable();
            $table->decimal('persen_klaim', 5, 2)->nullable();
            $table->decimal('nominal_klaim', 15, 2)->nullable();
        });
    }
};