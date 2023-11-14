<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartNumber extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'name', 'number', 'measurement_id', 'type_id', 'item_class_id', 'standard_package_id', 'quantity', 'workcenter_id', 'planner_id', 'project_id'
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

    public function productionPlans(): HasMany
    {
        return $this->hasMany(ProductionPlan::class, 'part_number_id');
    }

    public function scrapRecords(): HasMany
    {
        return $this->hasMany(ScrapRecord::class, 'part_number_id');
    }

    /**
     *
     */
    public function mainPartNumbers()
    {
        return $this->belongsToMany(PartNumber::class, 'part_hierarchies', 'sub_part_number_id', 'main_part_number_id')
            ->withPivot('required_quantity')
            ->withTimestamps();
    }

    /**
     *
     */
    public function subPartNumbers()
    {
        return $this->belongsToMany(PartNumber::class, 'part_hierarchies', 'main_part_number_id', 'sub_part_number_id')
            ->withPivot('required_quantity')
            ->withTimestamps();
    }
}
