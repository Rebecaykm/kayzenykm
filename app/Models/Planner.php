<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Planner extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'facility', 'name'
    ];

    public function partNumbers(): HasMany
    {
        return $this->hasMany(PartNumber::class);
    }
}
