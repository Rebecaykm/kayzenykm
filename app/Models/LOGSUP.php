<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LOGSUP extends Model
{
    use HasFactory;
    protected $table = '_logs_update_plan';
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
