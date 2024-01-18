<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YK006 extends Model
{
    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU01.YK006';

    protected $fillable = [
        'K6PROD',
        'K6WRKC',
        'K6SDTE',
        'K6EDTE',
        'K6DDTE',
        'K6DSHT',
        'K6PFQY',
        'K6CUSR',
        'K6CCDT',
        'K6CCTM',
        'K6FIL1',
        'K6FIL2'
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
