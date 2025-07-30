<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YHSCR extends Model
{
    use HasFactory;

    protected $connection = 'odbc-connection-lx834fu01';
    protected $table = 'LX834FU01.YHSCR';

    // Desactiva los timestamps automáticos
    public $timestamps = false;

    // Indica que no hay auto-incremento
    public $incrementing = false;

    // Indica que no hay clave primaria definida
    protected $primaryKey = null;

    protected $fillable = [
        'SCPROD',
        'SCSTDT',
        'SCRATE',
        'SCCRDT',
        'SCCRTM',
        'SCCRUS',
        'SCCRWS',
    ];
}
