<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('program_berjalan', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');

        $table->string('kode_customer');
        $table->foreign('kode_customer')->references('kode_customer')->on('customers')->onDelete('cascade');

        $table->string('kode_program');
        $table->foreign('kode_program')->references('kode_program')->on('programs')->onDelete('cascade');

        $table->date('start_date');
        $table->date('end_date');
        $table->string('target')->nullable();
        $table->string('pic')->nullable();
        $table->text('keterangan')->nullable();

        $table->decimal('budget', 15, 2)->nullable();
        $table->string('file_path')->nullable();

        // Klaim-related fields
        $table->decimal('nilai_klaim_per_item', 15, 2)->nullable();
        $table->decimal('persen_klaim', 5, 2)->nullable();
        $table->decimal('nominal_klaim', 15, 2)->nullable();

        $table->decimal('min_pembelian', 15, 2)->nullable();
        $table->decimal('reward', 15, 2)->nullable();
        $table->enum('reward_type', ['unit', 'rupiah', 'persen'])->nullable();

        $table->enum('status', ['draft', 'menunggu persetujuan', 'disetujui', 'ditolak', 'selesai'])->default('draft');
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('program_berjalan');
}

};
