<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KloterSummary extends Model
{
    use HasFactory;

    protected $table = 'kloter_summaries';
    protected $primaryKey = 'kloter_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kloter_id',
        'total_terjual',
        'total_berat_kg',
        'total_pemasukan',
        'total_pengeluaran',
        'total_mati',
        'total_panen',
        'stok_tersedia',
        'last_calculated_at',
    ];

    protected $casts = [
        'total_berat_kg' => 'decimal:2',
        'total_pemasukan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'last_calculated_at' => 'datetime',
    ];

    /**
     * Relationship to Kloter
     */
    public function kloter()
    {
        return $this->belongsTo(Kloter::class);
    }
}
