<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScrapRecord extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'production_plan_id', 'part_number_id', 'scrap_id', 'user_id', 'quantity', 'flag'
    ];

    public function productionPlan(): BelongsTo
    {
        return $this->belongsTo(ProductionPlan::class);
    }

    public function partNumber(): BelongsTo
    {
        return $this->belongsTo(PartNumber::class);
    }

    public function scrap(): BelongsTo
    {
        return $this->belongsTo(Scrap::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
