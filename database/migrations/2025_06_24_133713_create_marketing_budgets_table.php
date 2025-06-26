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
        Schema::create('marketing_budgets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained()->onDelete('cascade');
    $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');
    $table->string('periode'); // format YYYY-MM
    $table->decimal('nilai_budget', 15, 2);
    $table->decimal('sisa_budget', 15, 2)->nullable(); // opsional, bisa dihitung otomatis
    $table->text('keterangan')->nullable();
    $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_budgets');
    }
};
