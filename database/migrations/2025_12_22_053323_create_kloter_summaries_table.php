<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Create kloter_summaries table for materialized view
     */
    public function up(): void
    {
        Schema::create('kloter_summaries', function (Blueprint $table) {
            $table->unsignedBigInteger('kloter_id')->primary();

            // Calculated fields
            $table->integer('total_terjual')->default(0);
            $table->decimal('total_berat_kg', 10, 2)->default(0);
            $table->decimal('total_pemasukan', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->integer('total_mati')->default(0);
            $table->integer('total_panen')->default(0);
            $table->integer('stok_tersedia')->default(0);

            // Metadata
            $table->timestamp('last_calculated_at')->nullable();

            $table->foreign('kloter_id')->references('id')->on('kloters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kloter_summaries');
    }
};
