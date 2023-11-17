<?php

namespace App\Jobs;

use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Shift;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        $date = Carbon::parse($this->date)->format('Y-m-d');

        $item = PartNumber::where('number', $this->partNumber)->first();
        $openShift = Shift::where('abbreviation', substr($this->shift, 4, 1))->first();
        $status = Status::where('name', 'PENDIENTE')->first();

        $productionPlan = ProductionPlan::with(['partNumber', 'shift'])
            ->where('part_number_id', $item->id)
            ->where('date', $date)
            ->where('shift_id', optional($openShift)->id)
            ->firstOrNew();

        if (!$productionPlan->exists) {
            $productionPlan->fill([
                'part_number_id' => $item->id ?? null,
                'date' => $date ?? null,
                'shift_id' => optional($openShift)->id ?? null,
                'plan_quantity' => intval($this->quantity) ?? null,
                'status_id' => $status->id ?? null,
            ])->save();
        }
    }
}
