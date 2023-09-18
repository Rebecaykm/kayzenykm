<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'model', 'prefixe', 'client_id'
    ];

    function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function partNumbers(): HasMany
    {
        return $this->hasMany(PartNumber::class);
    }
}
