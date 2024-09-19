<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YT2 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YT2';

    public $timestamps = false;

    protected $fillable = [
        'Y2ORN',
        'Y2SQN',
        'Y2SNP',
        'Y2DAT',
        'Y2TIM',
        'Y2USR',
    ];
}
