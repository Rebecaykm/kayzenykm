<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YMLTM extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU01.YMLTM';

    protected $fillable = [
        'LTID',
        'LTPROD',
        'LTLDTM',
        'LTCUSR',
        'LTCCDT',
        'LTCCTM',
        'LTFIL1',
        'LTFIL2'

    ];
}
