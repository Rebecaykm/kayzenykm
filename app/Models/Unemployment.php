<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unemployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'unemployment_type_id'
    ];

    public function unemploymentType(): BelongsTo
    {
        return $this->belongsTo(UnemploymentType::class);
    }
}
