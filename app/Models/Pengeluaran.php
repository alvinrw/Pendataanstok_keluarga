<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kloter;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kloter_id',
        'kategori',
        'jumlah_pengeluaran',
        'tanggal_pengeluaran',
        'catatan',
    ];

    /**
     * Relasi: Setiap Pengeluaran "milik" satu Kloter.
     */
    public function kloter()
    {
        return $this->belongsTo(Kloter::class);
    }
}
