<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('data_penjualans', 'kloter_id')) {
            Schema::table('data_penjualans', function (Blueprint $table) {
                // We make it nullable first to prevent conflicts with existing rows
                // constrained to kloters table
                $table->foreignId('kloter_id')
                    ->nullable()
                    ->constrained('kloters')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_penjualans', function (Blueprint $table) {
            if (Schema::hasColumn('data_penjualans', 'kloter_id')) {
                // SQLite has limitations on dropping foreign keys, checking driver might be needed
                // But generally this is standard Laravel syntax
                $table->dropForeign(['kloter_id']);
                $table->dropColumn('kloter_id');
            }
        });
    }
};
