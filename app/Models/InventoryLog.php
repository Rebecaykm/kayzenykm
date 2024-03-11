<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number_id',
        'quantity',
        'capture_date',
        'order_number',
        'pack_number',
        'cycle_inventory_id',
    ];

    public function partNumber(): BelongsTo
    {
        return $this->belongsTo(PartNumber::class, 'part_number_id');
    }

    public function cycleInventory(): BelongsTo
    {
        return $this->belongsTo(CycleInventory::class, 'cycle_inventory_id');
    }
}
