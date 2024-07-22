<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YT4 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU02.YT4';

    public $timestamps = false;

    protected $fillable = [
        'Y4SINO',
        'Y4TINO',
        'Y4TQTY',
        'Y4PROD',
        'Y4ORDN',
        'Y4TORD',
        'Y4DAT',
        'Y4TIM',
        'Y4USR',
    ];
}
