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
    Schema::table('program_berjalan', function (Blueprint $table) {
        $table->decimal('nilai_klaim_per_item', 15, 2)->nullable();
        $table->decimal('persen_klaim', 5, 2)->nullable();
        $table->decimal('nominal_klaim', 15, 2)->nullable();
    });
}

public function down()
{
    Schema::table('program_berjalan', function (Blueprint $table) {
        $table->dropColumn(['nilai_klaim_per_item', 'persen_klaim', 'nominal_klaim']);
    });
}

};
