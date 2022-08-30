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

    /**
     * @param $value
     * @return string|null
     */
    // public function fromDateTime($value)
    // {
    //     return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    // }

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

    /**
     * Registration of changes to open orders in YF005
     *
     * @param string $swrkc
     * @param string $sddte
     * @param string $sord
     * @param string $sprod
     * @param string $sqreq
     * @param string $sqfin
     * @param string $cdte
     * @param string $canc
     * @return bool
     */
    public static function storeOpenOrder(string $swrkc, string $sddte, string $sord, string $sprod, string $sqreq, string $sqfin, string $cdte, string $canc)
    {
        return Yf005::query()->insert([
            'F5ID' => 'SO',
            'F5WRKC' => $swrkc,
            'F5DDTE' => $sddte,
            'F5ORD' => $sord,
            'F5PROD' => $sprod,
            'F5QREQ' => $sqreq,
            'F5QFIN' => $sqfin,
            'F5CDTE' => $cdte,
            'F5CAN' => $canc,
        ]);
    }
}
