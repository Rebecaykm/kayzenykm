<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unemployment extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'code', 'name', 'unemployment_type_id', 'abbreviation'
    ];

    /**
     *
     */
    public function unemploymentType(): BelongsTo
    {
        return $this->belongsTo(UnemploymentType::class);
    }

    /**
     *
     */
    public function unemploymentRecords(): HasMany
    {
        return $this->hasMany(UnemploymentRecord::class, 'unemployment_id');
    }

    /**
     *
     */
    public function lines(): BelongsToMany
    {
        return $this->belongsToMany(Line::class);
    }
}
