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
        Schema::table('kloters', function (Blueprint $table) {
            // Kolom baru untuk menyimpan sisa ayam hidup
            $table->integer('sisa_ayam_hidup')->default(0)->after('jumlah_doc');
            
            // Kolom baru untuk menyimpan total pengeluaran
            $table->unsignedBigInteger('total_pengeluaran')->default(0)->after('harga_beli_doc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kloters', function (Blueprint $table) {
            $table->dropColumn(['sisa_ayam_hidup', 'total_pengeluaran']);
        });
    }
};
