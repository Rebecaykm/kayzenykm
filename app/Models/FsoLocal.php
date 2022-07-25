<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
