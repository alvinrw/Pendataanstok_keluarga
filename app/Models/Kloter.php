<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kloter extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kloter',
        'status',
        'tanggal_mulai',
        'jumlah_doc',
        'harga_beli_doc',
        'stok_awal',
        'stok_tersedia',
        'tanggal_panen',
        'harga_jual_total',
        'sisa_ayam_hidup',    // <-- TAMBAHKAN INI
        'total_pengeluaran',  // <-- TAMBAHKAN INI
    ];

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function kematianAyams()
    {
        return $this->hasMany(KematianAyam::class);
    }
    
    public function dataPenjualans()
    {
        return $this->hasMany(DataPenjualan::class);
    }

    public function panens()
{
    return $this->hasMany(Panen::class);
}

}
