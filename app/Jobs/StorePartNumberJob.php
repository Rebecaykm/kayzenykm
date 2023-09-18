<?php

namespace App\Jobs;

use App\Models\ItemClass;
use App\Models\Measurement;
use App\Models\PartNumber;
use App\Models\Planner;
use App\Models\Type;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StorePartNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $partNumber;
    private $name;
    private $measurement;
    private $itemType;
    private $itemClass;
    private $planner;

    /**
     * Create a new job instance.
     */
    public function __construct($name, $partNumber, $measurement, $itemType, $itemClass, $planner)
    {
        $this->partNumber = $partNumber;
        $this->name = $name;
        $this->measurement = $measurement;
        $this->itemType = $itemType;
        $this->itemClass =  $itemClass;
        $this->planner = $planner;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $measurementType = Measurement::where('symbol', '=', $this->measurement)->first();
        $itemType = Type::where('abbreviation', '=', $this->itemType)->first();
        $itemClass = ItemClass::where('abbreviation', '=', $this->itemClass)->first();
        $plannerCode = Planner::where('code', $this->planner)->first();

        PartNumber::updateOrCreate(
            [
                'number' => $this->partNumber,
            ],
            [
                'name' => $this->name,
                'measurement_id' => $measurementType->id ?? null,
                'type_id' => $itemType->id ?? null,
                'item_class_id' => $itemClass->id ?? null,
                'planner_id' => $plannerCode->id ?? null,
            ],
        );
    }
}
