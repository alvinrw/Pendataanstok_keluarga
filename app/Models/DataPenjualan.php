<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPenjualan extends Model
{
    
protected $table = 'data_penjualans';

    protected $fillable = [
        'tanggal',
        'nama_pembeli',
        'jumlah_ayam_dibeli',
        'berat_total',
        'harga_asli',
        'diskon',
        'harga_total',
    ];
}
