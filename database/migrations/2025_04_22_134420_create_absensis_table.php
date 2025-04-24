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
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sales_marketing_id');
        $table->date('tanggal');
        $table->time('jam_masuk')->nullable();
        $table->time('jam_keluar')->nullable();
        $table->string('latitude')->nullable();
        $table->string('longitude')->nullable();
        $table->enum('status', ['Hadir', 'Izin', 'Alfa']);
        $table->text('keterangan')->nullable();
        $table->timestamps();

        $table->foreign('sales_marketing_id')->references('id')->on('sales_marketings')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
