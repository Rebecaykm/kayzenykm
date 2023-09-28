<?php

namespace App\Jobs;

use App\Models\KFP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductionPlanMigrationJob implements ShouldQueue
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
        $prodcutionPlans = KFP::query()->select('FPROD', 'FRDTE', 'FTYPE', 'FQTY', 'FCLAS', 'FDATE', 'FWHSE', 'FPCNO')->where('FTYPE', 'F')->orderBy('FRDTE', 'DESC')->get();

        foreach ($prodcutionPlans as $key => $prodcutionPlan) {
            StoreProductionPlanJob::dispatch(
                $prodcutionPlan->FPROD,
                $prodcutionPlan->FQTY,
                $prodcutionPlan->FRDTE,
                $prodcutionPlan->FPCNO
            );
        }
    }
}
