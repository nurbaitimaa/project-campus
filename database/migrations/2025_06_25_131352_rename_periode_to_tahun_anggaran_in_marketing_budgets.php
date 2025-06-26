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
    Schema::table('marketing_budgets', function (Blueprint $table) {
        $table->renameColumn('periode', 'tahun_anggaran');
    });
}

public function down()
{
    Schema::table('marketing_budgets', function (Blueprint $table) {
        $table->renameColumn('tahun_anggaran', 'periode');
    });
}

};
