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
    protected $fillable = ['press_part_number_id', 'plan_date', 'shift_id', 'component_code', 'hits'];

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
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
