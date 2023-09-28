<?php

namespace App\Jobs;

use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreProductionPlanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $partNumber;
    private $quantity;
    private $date;
    private $shift;

    /**
     * Create a new job instance.
     */
    public function __construct($partNumber, $quantity, $date, $shift)
    {
        $this->partNumber = $partNumber;
        $this->quantity = $quantity;
        $this->date = $date;
        $this->shift = $shift;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $item = PartNumber::where('number', $this->partNumber)->first();
        $openShift = Shift::where('abbreviation', substr($this->shift, 4, 1))->first();

        ProductionPlan::updateOrCreate(
            [
                'part_number_id' => $item->id ?? '',
                'date' => Carbon::parse($this->date)->format('Y-m-d') ?? '',
                'shift_id' => $openShift->id ?? null
            ],
            [
                'plan_quantity' => $this->quantity ?? ''
            ]
        );
    }
}
