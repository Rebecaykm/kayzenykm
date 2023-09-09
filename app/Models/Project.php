<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'model', 'prefixe'
    ];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'clients_projects');
    }

    public function partNumbers(): HasMany
    {
        return $this->hasMany(PartNumber::class);
    }
}
