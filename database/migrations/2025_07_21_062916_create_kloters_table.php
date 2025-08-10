<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kloters', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kloter')->unique();
            $table->unsignedInteger('stok_awal');
            $table->unsignedInteger('stok_tersedia');
            $table->unsignedInteger('total_terjual')->default(0);
            $table->decimal('total_berat', 10, 2)->default(0);
            $table->decimal('total_pemasukan', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kloters');
    }
};