<?php

namespace App\Jobs;

use App\Models\IIM;
use App\Models\ItemClass;
use App\Models\Measurement;
use App\Models\PartNumber;
use App\Models\Planner;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PartNumberMigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $partNumbers = IIM::query()->select('IDESC', 'IPROD', 'IOPB', 'IUMS', 'IITYP', 'ICLAS', 'IMSPKT', 'IMBOXQ', 'IBUYC', 'IREF04', 'IMENDT', 'IMENTM')->where('IID', 'IM')->orderBy('IMENDT', 'ASC')->get();

        foreach ($partNumbers as $key => $partNumber) {

            $measurementType = Measurement::where('symbol', '=', $partNumber->IUMS)->first();
            $itemType = Type::where('abbreviation', '=', $partNumber->IITYP)->first();
            $itemClass = ItemClass::where('abbreviation', '=', $partNumber->ICLAS)->first();
            $plannerCode = Planner::where('code', $partNumber->IBUYC)->first();

            Log::info($partNumber->IPROD . ', ' . $partNumber->IDESC . ', ' . $partNumber->IUMS . ', ' . $partNumber->IITYP . ', ' . $partNumber->ICLAS . ', ' . $partNumber->IBUYC);

            PartNumber::updateOrCreate(
                [
                    'number' => $partNumber->IPROD,
                ],
                [
                    'name' => preg_replace('([^A-Za-z0-9])', '', $partNumber->IDESC),
                    'measurement_id' => $measurementType->id ?? null,
                    'type_id' => $itemType->id ?? null,
                    'item_class_id' => $itemClass->id ?? null,
                    'planner_id' => $plannerCode->id ?? null,
                ],
            );
        }
    }
}
