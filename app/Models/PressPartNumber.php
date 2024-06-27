<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PressPartNumber extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    /**
     *
     */
    protected $fillable = ['part_number', 'pieces_per_hit'];

    /**
     *
     */
    public function partNumbers(): BelongsToMany
    {
        return $this->belongsToMany(PartNumber::class, 'part_number_press_part_number', 'press_part_number_id', 'part_number_id');
    }

    /**
     *
     */
    public function pressProductionPlans(): HasMany
    {
        return $this->hasMany(PressProductionPlan::class, 'press_part_number_id');
    }
}
