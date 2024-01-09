<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YF020 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YF020';

    protected $fillable = [
        'YSWRKC',
        'YSWRKN',
        'YSRDTE',
        'YSSHFT',
        'YSPPNO',
        'YSPROD',
        'YSQSCR',
        'YSSCRE',
        'YSCRDT',
        'YSCRTM',
        'YSCRUS',
        'YSCRWS',
        'YSFIL1',
        'YSFIL2',
    ];
}
