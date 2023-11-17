<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YMCOM extends Model
{
    use HasFactory;
    protected $connection = 'odbc-connection-lx834fu02';
    protected $table = 'LX834FU02.YMCOM';
}
