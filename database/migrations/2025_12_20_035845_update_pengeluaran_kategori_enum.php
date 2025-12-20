<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support ALTER COLUMN for ENUM, so we need to recreate the table
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });

        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->enum('kategori', ['Pakan', 'Obat', 'Listrik/Air', 'Tenaga Kerja', 'Pemeliharaan Kandang', 'Lainnya', 'DOC'])->after('kloter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });

        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->enum('kategori', ['Pakan', 'Obat', 'Lainnya'])->after('kloter_id');
        });
    }
};
