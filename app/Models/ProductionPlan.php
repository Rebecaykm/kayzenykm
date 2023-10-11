<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number_id', 'plan_quantity', 'production_quantity', 'date', 'shift_id'
    ];

    public function partNumber(): BelongsTo
    {
        return $this->belongsTo(PartNumber::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function scrapRecords(): HasMany
    {
        return $this->hasMany(ScrapRecord::class, 'production_plan_id');
    }
}
