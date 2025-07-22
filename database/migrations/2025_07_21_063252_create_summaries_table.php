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
    Schema::create('summaries', function (Blueprint $table) {
        $table->id();
            $table->integer('stok_ayam')->default(0);
             $table->integer('total_ayam_terjual')->default(0);      // jumlah ayam terjual
        $table->integer('total_berat_tertimbang')->default(0);  // total berat gram
        $table->bigInteger('total_pemasukan')->default(0);      // estimasi nilai (rupiah)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summaries');
    }
};
