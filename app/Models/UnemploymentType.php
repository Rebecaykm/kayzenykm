<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnemploymentType extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'name'
    ];

    public function unemployments(): HasMany
    {
        return $this->hasMany(Unemployment::class, 'unemployment_type_id');
    }
}