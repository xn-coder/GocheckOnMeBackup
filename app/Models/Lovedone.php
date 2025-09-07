<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lovedone extends Model
{
    use HasFactory;
    protected $table = 'lovedones';
    // protected $guard =[];
    protected $fillable = [
        'user_id', 
        'phone_no', 
        'timezone'
    ];
}
