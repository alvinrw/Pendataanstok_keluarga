<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class simpan_data extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_ayam_terjual',
        'total_berat_tertimbang',
        'total_pemasukan',
    ];
}
