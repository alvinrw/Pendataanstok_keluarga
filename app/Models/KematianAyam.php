<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kloter;

class KematianAyam extends Model
{
    use HasFactory;

    // Ganti nama tabel default karena Laravel akan menganggapnya 'kematian_ayams'
    protected $table = 'kematian_ayams';

    protected $fillable = [
        'kloter_id',
        'jumlah_mati',
        'tanggal_kematian',
        'catatan',
    ];

    /**
     * Relasi: Setiap data Kematian "milik" satu Kloter.
     */
    public function kloter()
    {
        return $this->belongsTo(Kloter::class);
    }
}
