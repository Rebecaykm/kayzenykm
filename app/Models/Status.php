<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'name'
    ];

    /**
     *
     */
    public function productionPlans(): HasMany
    {
        return $this->hasMany(ProductionPlan::class);
    }

    /**
     *
     */
    public function productionRecords(): HasMany
    {
        return $this->hasMany(ProductionPlan::class);
    }

    /**
     *
     */
    public function cycleInventories(): HasMany
    {
        return $this->hasMany(CycleInventory::class);
    }

    /**
     *
     */
    public function pressProductionPlans(): HasMany
    {
        return $this->hasMany(PressProductionPlan::class);
    }
}
