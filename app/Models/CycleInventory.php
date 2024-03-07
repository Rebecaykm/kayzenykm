<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CycleInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_made_id',
        'user_validating_id',
        'status_id',
        'start_date',
        'end_date',
    ];

    public function userMade(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_made_id');
    }

    public function userValidating(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_validating_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class, 'cycle_inventory_id');
    }
}
