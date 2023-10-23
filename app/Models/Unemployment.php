<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unemployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'unemployment_type_id'
    ];

    public function unemploymentType(): BelongsTo
    {
        return $this->belongsTo(UnemploymentType::class);
    }

    public function unemploymentRecords(): HasMany
    {
        return $this->hasMany(UnemploymentRecord::class, 'unemployment_id');
    }
}
