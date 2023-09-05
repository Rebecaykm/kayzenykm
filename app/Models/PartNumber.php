<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'number'
    ];

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(Measurement::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function itemClass(): BelongsTo
    {
        return $this->belongsTo(ItemClass::class);
    }

    public function standardPackage(): BelongsTo
    {
        return $this->belongsTo(StandardPackage::class);
    }

    public function workcenter(): BelongsTo
    {
        return $this->belongsTo(Workcenter::class);
    }

    public function planner(): BelongsTo
    {
        return $this->belongsTo(Planner::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
