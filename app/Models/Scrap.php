<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scrap extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'type_scrap_id'
    ];

    public function typeScrap(): BelongsTo
    {
        return $this->belongsTo(TypeScrap::class);
    }

    public function scrapRecords(): HasMany
    {
        return $this->hasMany(ScrapRecord::class, 'scrap_id');
    }
}
