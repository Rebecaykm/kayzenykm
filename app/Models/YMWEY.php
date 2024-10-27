<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class YMWEY extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834f01';
    protected $table = 'LX834F01.YMWEY';

    /**
     * @param $value
     * @return string|null
     */


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

}
