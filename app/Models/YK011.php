<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YK011 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU01.YK011';

    protected $fillable = [
        'CID',
        'CPROD',
        'CICLAS',
        'CRDTE',
        'CRQTY',
        'CRSNP',
        'CRBOXQ',
        'CRFAC',
        'CRWRKC',
        'CRWHS'
    ];
}
