<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kloter_id',
        'jumlah_panen',
        'tanggal_panen',
    ];

    public function kloter()
    {
        return $this->belongsTo(Kloter::class);
    }
}