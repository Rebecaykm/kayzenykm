<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workcenter extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'number', 'name', 'description', 'departament_id', 'printer_id'
    ];

    public function departament(): BelongsTo
    {
        return $this->belongsTo(Departament::class);
    }

    public function partNumbers(): HasMany
    {
        return $this->hasMany(PartNumber::class);
    }

    public function unemploymentRecords(): HasMany
    {
        return $this->hasMany(UnemploymentRecord::class, 'workcenter_id');
    }

    public function printer(): BelongsTo
    {
        return $this->belongsTo(Printer::class);
    }
}
