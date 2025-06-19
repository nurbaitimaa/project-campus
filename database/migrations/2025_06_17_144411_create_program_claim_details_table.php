<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('program_claim_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_claim_id')->constrained()->onDelete('cascade');
            $table->string('nama_outlet');
            $table->double('penjualan')->default(0);
            $table->double('klaim_distributor')->default(0);
            $table->double('klaim_sistem')->default(0);
            $table->double('selisih')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_claim_details');
    }
};
