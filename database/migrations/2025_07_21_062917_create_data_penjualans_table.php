<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_penjualans', function (Blueprint $table) {
            $table->id();

            // Kolom untuk relasi ke tabel 'kloters'
            $table->foreignId('kloter_id')
                  ->constrained('kloters') // Menghubungkan ke tabel 'kloters'
                  ->onDelete('cascade');   // Jika kloter dihapus, data penjualan terkait juga ikut terhapus

            $table->date('tanggal');
            $table->string('nama_pembeli');
            $table->integer('jumlah_ayam_dibeli');
            $table->float('berat_total');
            $table->bigInteger('harga_asli');
            $table->boolean('diskon'); // Mengubah tipe data diskon menjadi boolean (0 atau 1)
            $table->bigInteger('harga_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_penjualans');
    }
};