<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Measurement extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'symbol', 'unit'
    ];

    public function partNumbers(): HasMany
    {
        return $this->hasMany(PartNumber::class, 'measurement');
    }
}
