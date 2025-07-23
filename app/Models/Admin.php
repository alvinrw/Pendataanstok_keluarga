<?php



// app/Models/Admin.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins'; // 👈 Tambahkan baris ini

    protected $fillable = ['username', 'password'];

    public $timestamps = true;
}
