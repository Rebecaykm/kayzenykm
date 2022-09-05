<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'tblChecada';

    use HasFactory;
}
