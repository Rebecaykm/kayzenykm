<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departament extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'code', 'name',
    ];

    /**
     *
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     *
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     *
     */
    public function lines(): HasMany
    {
        return $this->hasMany(Line::class);
    }
}
