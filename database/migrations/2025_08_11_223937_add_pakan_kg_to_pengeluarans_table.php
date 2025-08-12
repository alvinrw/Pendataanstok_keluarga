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
        Schema::table('pengeluarans', function (Blueprint $table) {
            // Kolom baru untuk berat pakan, boleh kosong (nullable)
            // karena hanya diisi jika kategori adalah 'Pakan'.
            $table->float('jumlah_pakan_kg')->nullable()->after('jumlah_pengeluaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropColumn('jumlah_pakan_kg');
        });
    }
};
