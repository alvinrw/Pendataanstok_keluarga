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
    Schema::create('panens', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kloter_id')->constrained('kloters')->onDelete('cascade');
        $table->integer('jumlah_panen');
        $table->date('tanggal_panen');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panens');
    }
};
