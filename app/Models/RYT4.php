<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RYT4 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU02.RYT4';

    protected $fillable = [
        'R4SINO',
        'R4TINO',
        'R4TQTY',
        'R4PROD',
        'R4ORDN',
        'R4TORD',
        'R4USED',
        'R4DAT',
        'R4TIM',
        'R4USR'
    ];
}
