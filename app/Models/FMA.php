<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FMA extends Model
{
    use HasFactory;
    protected $connection = 'odbc-connection-lx834f01';
    protected $table = 'LX834F01.FMA';
}