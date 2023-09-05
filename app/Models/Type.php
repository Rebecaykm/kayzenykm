<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'abbreviation', 'name'
    ];

    public function partNumbers(): HasMany
    {
        return $this->hasMany(PartNumber::class);
    }
}
