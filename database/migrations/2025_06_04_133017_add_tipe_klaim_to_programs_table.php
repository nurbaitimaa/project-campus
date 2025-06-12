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
    Schema::table('programs', function (Blueprint $table) {
        $table->enum('tipe_klaim', ['rupiah', 'unit', 'persen'])->after('parameter_klaim')->nullable();
    });
}

public function down()
{
    Schema::table('programs', function (Blueprint $table) {
        $table->dropColumn('tipe_klaim');
    });
}

};
