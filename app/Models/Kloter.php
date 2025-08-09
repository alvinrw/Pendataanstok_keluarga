<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kloter extends Model
{
    protected $fillable = [
        'nama_kloter',
        'stok_awal',
        'stok_tersedia',
        'total_terjual',
        'total_berat',
        'total_pemasukan'
    ];
}