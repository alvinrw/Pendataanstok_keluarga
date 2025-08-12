<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'kloter_id',
        'kategori',
        'jumlah_pengeluaran',
        'tanggal_pengeluaran',
        'catatan',
        'jumlah_pakan_kg', // <-- TAMBAHKAN BARIS INI
    ];

    /**
     * Relasi: Setiap Pengeluaran "milik" satu Kloter.
     */
    public function kloter()
    {
        return $this->belongsTo(Kloter::class);
    }
}
