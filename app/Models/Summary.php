<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    protected $table = 'summaries';

    protected $fillable = [
    'stok_ayam',
    'total_ayam_terjual',
    'total_berat_tertimbang',
    'total_pemasukan',
    
];

}