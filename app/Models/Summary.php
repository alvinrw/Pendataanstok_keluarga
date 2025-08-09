<?php

namespace App\Models; // <-- Diubah ke sini

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory; // Sebaiknya ditambahkan juga

    /**
     * Nama tabel yang digunakan oleh model.
     *
     * @var string
     */
    protected $table = 'summaries';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stok_ayam',
        'total_ayam_terjual',
        'total_berat_tertimbang',
        'total_pemasukan',
    ];
}