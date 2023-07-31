<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lwk extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834f01';
    protected $table = 'LX834F01.LWK';

    protected $fillable = [
        'WWRKC',
        'WDESC',
    ];

    /**
     * @param $value
     * @return string|null
     */
    public function fromDateTime($value)
    {
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
