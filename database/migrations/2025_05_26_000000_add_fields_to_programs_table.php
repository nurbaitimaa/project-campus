<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('jenis_program')->nullable()->after('deskripsi');
            $table->string('parameter_klaim')->nullable()->after('jenis_program');
            
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['jenis_program', 'parameter_klaim']);
        });
    }
};
