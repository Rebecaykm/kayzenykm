<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPYF013 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834f02';
    protected $table = 'LX834FU02.YF013';

    protected $fillable = [
        'YFWRKC',
        'YFWRKN',
        'YFRDTE',
        'YFSHFT',
        'YFPPNO',
        'YFPROD',
        'YFSTIM',
        'YFETIM',
        'YFSDT',
        'YFEDT',
        'YFQPLA',
        'YFQPRO',
        'YFQSCR',
        'YFSCRE',
        'YFCRDT',
        'YFCRTM',
        'YFCRUS',
        'YFCRWS',
        'YFFIL1',
        'YFFIL2',
    ];
}
