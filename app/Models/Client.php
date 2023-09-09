<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name'
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'clients_projects');
    }
}
