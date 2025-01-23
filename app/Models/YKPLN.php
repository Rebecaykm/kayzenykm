<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YKPLN extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU01.YKPLN';
}
