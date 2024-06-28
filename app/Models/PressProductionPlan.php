<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PressProductionPlan extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    /**
     *
     */
    protected $fillable = ['press_part_number_id', 'plan_date', 'shift_id', 'component_code', 'planned_hits', 'produced_hits', 'scrap_quantity'];

    /**
     *
     */
    public function pressPartNumber(): BelongsTo
    {
        return $this->belongsTo(PressPartNumber::class, 'press_part_number_id');
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
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
