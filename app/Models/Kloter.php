<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengeluaran;
use App\Models\KematianAyam;

class Kloter extends Model
{
    use HasFactory;

    // Menambahkan semua kolom baru agar bisa diisi secara otomatis
    protected $fillable = [
        'nama_kloter',
        'status',
        'tanggal_mulai',
        'jumlah_doc',
        'harga_beli_doc',
        'stok_awal', // Kolom lama, biarkan saja
        'stok_tersedia', // Kolom lama, biarkan saja
        'tanggal_panen',
        'harga_jual_total',
    ];

    /**
     * Relasi: Satu Kloter memiliki banyak data Pengeluaran.
     */
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }


    // Di dalam file app/Models/Kloter.php

public function dataPenjualans()
{
    return $this->hasMany(DataPenjualan::class);
}


    /**
     * Relasi: Satu Kloter memiliki banyak data Kematian Ayam.
     */
    public function kematianAyams()
    {
        return $this->hasMany(KematianAyam::class);
    }
}