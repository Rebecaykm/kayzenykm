<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Line extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'name',
        'departament_id'
    ];

    /**
     *
     */
    public function departament(): BelongsTo
    {
        return $this->belongsTo(Departament::class, 'departament_id', 'id');
    }

    /**
     *
     */
    public function workcenters(): HasMany
    {
        return $this->hasMany(Workcenter::class);
    }

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
    public function unemployments(): BelongsToMany
    {
        return $this->belongsToMany(Unemployment::class);
    }
}
