<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Printer extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'name', 'model', 'ip', 'port'
    ];

    public function workcenters(): HasMany
    {
        return $this->hasMany(Workcenter::class);
    }
}
