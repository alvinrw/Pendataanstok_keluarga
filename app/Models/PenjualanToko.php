<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanToko extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'total_harga',
        'catatan'
    ];
}

