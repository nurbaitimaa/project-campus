<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('program_berjalan', function (Blueprint $table) {
            // Hapus kolom budget karena sekarang dikelola secara terpusat
            $table->dropColumn('budget');
        });
    }

    public function down(): void {
        Schema::table('program_berjalan', function (Blueprint $table) {
            // Untuk bisa rollback jika diperlukan
            $table->decimal('budget', 15, 2)->nullable();
        });
    }
};