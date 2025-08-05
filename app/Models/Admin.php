<?php

namespace App\Models;

// 1. TAMBAHKAN 'use' STATEMENT DI BAWAH INI
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 2. UBAH DEKLARASI CLASS, TAMBAHKAN "implements Authenticatable"
class Admin extends Model implements Authenticatable
{
    // 3. TAMBAHKAN 'AuthenticatableTrait' DI DALAM "use"
    use HasFactory, AuthenticatableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

       protected $table = 'admins'; 
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
}