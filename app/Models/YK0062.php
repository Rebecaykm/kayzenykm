<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//  nuevo proceso offset

class YK0062 extends Model
{
    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YK0062';

    protected $fillable = [
        'K62PRO',
        'K62WRK',
        'K62SDT',
        'K62EDT',
        'K62DDT',
        'K62DSH',
        'K62PFQ',
        'K62CUS',
        'K62CCD',
        'K62CCT',
        'K62FI1',
        'K62FI2'
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
