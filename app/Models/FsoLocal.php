<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FsoLocal extends Model
{
    use HasFactory;

    protected $fillable = [
        'SID',
        'SWRKC',
        'SDDTE',
        'SORD',
        'SPROD',
        'SQREQ',
        'SQFIN',
        'CDTE',
        'CANC',
    ];
}
