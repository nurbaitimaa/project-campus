<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKlaimRulesToProgramsTable extends Migration
{
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->decimal('min_pembelian', 15, 2)->nullable()->after('parameter_klaim');
            $table->decimal('reward', 15, 2)->nullable()->after('min_pembelian');
            $table->enum('reward_type', ['unit', 'rupiah', 'persen'])->nullable()->after('reward');
        });
    }

    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['min_pembelian', 'reward', 'reward_type']);
        });
    }
}
