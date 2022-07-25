<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yf005 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YF005';

    protected $fillable = [
        'F5ID',
        'F5WRKC',
        'F5DDTE',
        'F5ORD',
        'F5PROD',
        'F5QREQ',
        'F5QFIN',
        'F5CDTE',
        'F5CAN',
    ];

    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
