<?php

namespace App\Jobs;

use App\Models\ProdcutionRecord;
use App\Models\ProductionPlan;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CompletionProductionPlan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $productionPlan;

    /**
     * Create a new job instance.
     */
    public function __construct(ProductionPlan $productionPlan)
    {
        $this->productionPlan = $productionPlan;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $status = Status::where('name', 'INACTIVO')->first();

        $records = ProdcutionRecord::where('production_plan_id', $this->productionPlan->id)->get();

        $dateStart = Carbon::parse($records->min('created_at'))->format('Ymd');
        $dateEnd = Carbon::parse($records->max('created_at'))->format('Ymd');

        $timeStart = Carbon::parse($records->min('time_start'))->format('Hi');
        $timeEnd = Carbon::parse($records->max('time_end'))->format('Hi');

        StoreIPYF013Job::dispatch(
            $this->productionPlan,
            $timeStart,
            $timeEnd,
            $dateStart,
            $dateEnd,
        );

        StoreYF020Job::dispatch(
            $this->productionPlan
        );

        $this->productionPlan->update(['status_id' => $status->id]);
    }
}
