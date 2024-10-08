<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YK007 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YK007';

    protected $fillable = [
        'DID',
        'DPROD',
        'DRSDT',
        'DREDT',
        'DROQY',
        'DRFQY',
        'DRDQY',
        'DRDAV',
        'DRCWS',
        'DRCBY',
        'DRCDT',
        'DRCTM'
    ];
}
