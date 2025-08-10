<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPenjualan extends Model
{
    use HasFactory;
    
    protected $table = 'data_penjualans';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = [
        'tanggal',
        'nama_pembeli',
        'jumlah_ayam_dibeli',
        'berat_total',
        'harga_asli',
        'diskon',
        'harga_total',
        'kloter_id', // <-- TAMBAHKAN BARIS INI
    ];

    /**
     * Mendefinisikan relasi ke model Kloter.
     */
    public function kloter()
    {
        return $this->belongsTo(Kloter::class);
    }
}