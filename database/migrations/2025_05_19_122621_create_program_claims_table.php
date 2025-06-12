<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('program_claims', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // KLM20240517001
            $table->date('tanggal_klaim');

            // Pastikan foreign key merujuk ke tabel yang benar
            $table->unsignedBigInteger('program_berjalan_id');
            $table->foreign('program_berjalan_id')
                  ->references('id')
                  ->on('program_berjalan')
                  ->onDelete('cascade');

            $table->decimal('total_pembelian', 15, 2);
            $table->integer('jumlah_unit')->nullable(); // Optional
            $table->decimal('total_klaim', 15, 2)->nullable(); // Otomatis dihitung
            $table->string('bukti_klaim')->nullable(); // Path ke file

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_claims');
    }
};
