<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'abbreviation', 'name'
    ];

    public function productionPlans(): HasMany
    {
        return $this->hasMany(ProductionPlan::class, 'shift_id');
    }
}
