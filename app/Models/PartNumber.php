<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PartNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'number', 'measurement_id', 'type_id', 'item_class_id', 'standard_package_id', 'workcenter_id', 'planner_id', 'project_id'
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

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'part_numbers_projects', 'part_number_id', 'project_id');
    }
}
