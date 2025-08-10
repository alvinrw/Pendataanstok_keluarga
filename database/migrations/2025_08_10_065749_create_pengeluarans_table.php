<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kloter_id')->constrained('kloters')->onDelete('cascade');
            $table->enum('kategori', ['Pakan', 'Obat', 'Lainnya']);
            $table->unsignedBigInteger('jumlah_pengeluaran');
            $table->date('tanggal_pengeluaran');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};