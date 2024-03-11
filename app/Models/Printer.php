<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Printer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ip', 'model'
    ];

    public function workcenters(): HasMany
    {
        return $this->hasMany(Workcenter::class);
    }
}
