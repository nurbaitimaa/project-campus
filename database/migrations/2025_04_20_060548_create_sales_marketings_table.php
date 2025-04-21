<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('sales_marketings', function (Blueprint $table) {
        $table->id();
        $table->string('kode_sales')->unique();
        $table->string('nama_sales');
        $table->string('email')->nullable();
        $table->string('telepon')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_marketings');
    }
};
