<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834f02';
    protected $table = 'LX834F02.FSO';

    protected $fillable = [
        'SWRKC',
        'SDDTE',
        'SORD',
        'SPROD',
        'SQREQ',
        'SQFIN',
    ];
}
