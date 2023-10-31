<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnemploymentRecord extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'user_id', 'workcenter_id', 'unemployment_id', 'time_start', 'time_end', 'minutes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workcenter(): BelongsTo
    {
        return $this->belongsTo(Workcenter::class);
    }

    public function unemployment(): BelongsTo
    {
        return $this->belongsTo(Unemployment::class);
    }
}
