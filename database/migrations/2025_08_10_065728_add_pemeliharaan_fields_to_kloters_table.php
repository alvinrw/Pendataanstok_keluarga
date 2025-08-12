<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kloters', function (Blueprint $table) {
            // Kolom baru sesuai rencana kita
            $table->enum('status', ['Aktif', 'Selesai Panen'])->default('Aktif')->after('nama_kloter');
            $table->date('tanggal_mulai')->nullable()->after('status');
            $table->integer('jumlah_doc')->default(0)->after('tanggal_mulai');
            $table->unsignedBigInteger('harga_beli_doc')->default(0)->after('jumlah_doc');
            
            // Kolom untuk data panen, diisi nanti
            $table->date('tanggal_panen')->nullable();
            $table->unsignedBigInteger('harga_jual_total')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kloters', function (Blueprint $table) {
            // Untuk membatalkan migrasi jika diperlukan
            $table->dropColumn(['status', 'tanggal_mulai', 'jumlah_doc', 'harga_beli_doc', 'tanggal_panen', 'harga_jual_total']);
        });
    }
};