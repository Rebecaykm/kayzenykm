<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'model', 'prefixe', 'client_id'
    ];

    function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function partNumbers(): BelongsToMany
    {
        return $this->belongsToMany(PartNumber::class, 'part_numbers_projects', 'project_id', 'part_number_id');
    }
}
