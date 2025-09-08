<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'lovedone_number', 
        'call_status',
        'call_sid',
        'call_duration',
        'recording_url',
        'initiated_at',
        'answered_at',
        'completed_at'
    ];
}
