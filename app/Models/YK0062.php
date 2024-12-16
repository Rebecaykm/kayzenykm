<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//  nuevo proceso offset

class YK0062 extends Model
{
    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YK006';

    protected $fillable = [
        'K62PROD',
        'K62WRKC',
        'K62SDTE',
        'K62EDTE',
        'K62DDTE',
        'K62DSHT',
        'K62PFQY',
        'K62CUSR',
        'K62CCDT',
        'K62CCTM',
        'K62FIL1',
        'K62FIL2'
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
