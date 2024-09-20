<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YT1 extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU01.YT1';

    public $timestamps = false;

    protected $fillable = [
        'Y1ORN',
        'Y1SQN',
        'Y1SNP',
        'Y1DAT',
        'Y1TIM',
        'Y1USR',
    ];
}
