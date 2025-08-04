<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = ['tanggal', 'kategori', 'jumlah', 'deskripsi'];
}
