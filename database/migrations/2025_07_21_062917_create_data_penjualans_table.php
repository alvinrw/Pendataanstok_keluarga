<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('data_penjualans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_pembeli');
            $table->integer('jumlah_ayam_dibeli');
            $table->float('berat_total');
            $table->bigInteger('harga_asli');
            $table->integer('diskon');
            $table->bigInteger('harga_total');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_penjualans');
    }
};
