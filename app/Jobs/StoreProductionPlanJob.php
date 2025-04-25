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
        $partNumber = PartNumber::query()->where('number', $this->partNumber)->first();

        if (!$partNumber) {
            Log::error('PartNumber no encontrado', ['number' => $this->partNumber]);
            return;
        }

        $shift = Shift::query()->where('abbreviation', $this->shift)->first();

        if (!$shift) {
            Log::error('Shift no encontrado', ['abbreviation' => $this->shift]);
            return;
        }

        $status = Status::where('name', 'PENDIENTE')->first();

        if (!$status) {
            Log::error('Status PENDIENTE no encontrado');
            return;
        }

        $productionPlan = ProductionPlan::query()
            ->where('part_number_id', $partNumber->id)
            ->where('plan_quantity', $this->quantity)
            ->where('date', $this->date)
            ->where('shift_id', $shift->id)
            ->first();

        if ($productionPlan === null) {
            ProductionPlan::create([
                'part_number_id' => $partNumber->id,
                'plan_quantity' => $this->quantity,
                'date' => $this->date,
                'shift_id' => $shift->id,
                'status_id' => $status->id
            ]);
        }
    }
}
