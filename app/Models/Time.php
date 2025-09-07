<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    protected $table = 'times';
    // protected $guard =[];
    protected $fillable = [
        'user_id', 
        'day', 
        'time1',
        'am_pm1',
        'time2',
        'am_pm2',
        'time3',
        'am_pm3'
    ];
}
