<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yf006 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YF006';

    /**
     * @param $value
     * @return string|null
     */
    // public function fromDateTime($value)
    // {
    //     return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    // }

    protected $fillable = [
        'F6ID',
        'F6WRKC',
        'F6DDTE',
        'F6ORD',
        'F6PROD',
        'F6QREQ',
        'F6QFIN',
        'F6QREN',
        'F6CAN',
        'F6CDTE',
    ];

    public static function storeDailyProduction(string $sid, string $swrkc, string $sddte, string $sord, string $sprod, string $sqreq, float $sqfin, float $sqremm, int $canc, string $cdte)
    {
        return Yf006::query()->insert([
            'F6ID' => $sid,
            'F6WRKC' => $swrkc,
            'F6DDTE' => $sddte,
            'F6ORD' => $sord,
            'F6PROD' => $sprod,
            'F6QREQ' => $sqreq,
            'F6QFIN' => $sqfin,
            'F6QREJ' => $sqremm,
            'F6CAN' => $canc,
            'F6CDTE' => $cdte,
        ]);
    }
}
