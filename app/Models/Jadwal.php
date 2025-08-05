<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'activity_name',
        'date',
        'start_time',
        'location',
        'description',
        'priority',
    ];
}
