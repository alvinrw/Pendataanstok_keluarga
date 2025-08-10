<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kematian_ayams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kloter_id')->constrained('kloters')->onDelete('cascade');
            $table->integer('jumlah_mati');
            $table->date('tanggal_kematian');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kematian_ayams');
    }
};