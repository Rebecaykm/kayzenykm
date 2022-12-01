<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departament extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name',
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }

    /**
     * @param $value
     * @return string|null
     */
    // public function fromDateTime($value)
    // {
    //     return Carbon::parse(parent::fromDateTime($value))->format('Y-d-m H:i:s');
    // }
}
