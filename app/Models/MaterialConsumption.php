<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialConsumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'pack_number', 'production_plan_id'
    ];

    /**
     *
     */
    public function productionPlan(): BelongsTo
    {
        return $this->belongsTo(ProductionPlan::class);
    }
}
