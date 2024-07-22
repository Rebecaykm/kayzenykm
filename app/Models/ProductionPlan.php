<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'part_number_id', 'plan_quantity', 'production_quantity', 'date', 'shift_id', 'status_id', 'scrap_quantity', 'temp', 'production_start', 'spm'
    ];

    /**
     *
     */
    public function partNumber(): BelongsTo
    {
        return $this->belongsTo(PartNumber::class);
    }

    /**
     *
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     *
     */
    public function scrapRecords(): HasMany
    {
        return $this->hasMany(ScrapRecord::class, 'production_plan_id');
    }

    /**
     *
     */
    public function productionRecords(): HasMany
    {
        return $this->hasMany(ProdcutionRecord::class, 'production_plan_id');
    }

    /**
     *
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     *
     */
    public function materialConsumptions(): HasMany
    {
        return $this->hasMany(MaterialConsumption::class);
    }
}
