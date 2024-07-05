<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabelPrint extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'print_count', 'print_reason', 'prodcution_record_id', 'user_id'
    ];

    /**
     *
     */
    public function prodcutionRecord(): BelongsTo
    {
        return $this->belongsTo(ProdcutionRecord::class, 'prodcution_record_id');
    }

    /**
     *
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
