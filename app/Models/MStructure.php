<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MStructure extends Model
{
    use HasFactory;
    protected $table = 'structures';

    protected $fillable = [
        'final',
        'componente',
        'clase',
        'Activo'
    ];

    /**
     * @param $value
     * @return string|null
     */
    public function fromDateTime($value)
    {
        //dddf
        return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    }
}
