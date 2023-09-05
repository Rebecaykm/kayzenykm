<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol', 'unit'
    ];

    protected $dateFormat='Y-m-d H:i:s.v';

    public function partNumbers(): HasMany
    {
        return $this->hasMany(PartNumber::class, 'measurement');
    }
}
