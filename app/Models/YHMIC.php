<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YHMIC extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU01.YHMIC';

    protected $fillable = [
        'YIDEDT',
        'YISINO',
        'YIPCNO',
        'YIPQTY',
        'YIPROD',
        'YIORDN',
        'YITORD',
        'YITREC',
        'YICOMM',
        'YIICFL',
        'YIRRFL',
        'YIFIL1',
        'YIFIL2',
        'YICDT',
        'YICTM',
        'YICUS',
        'YIUDT',
        'YIUTM',
        'YIUUS',
    ];
}