<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workcenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'name', 'description', 'departament_id'
    ];

    public function departament(): BelongsTo
    {
        return $this->belongsTo(Departament::class);
    }
}
