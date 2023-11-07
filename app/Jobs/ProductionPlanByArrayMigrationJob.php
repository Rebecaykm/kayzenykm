<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionPlanByArrayMigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data as $item) {

            $partNumber = $item['part_number'];

            Log::info("Número de parte: $partNumber");

            $prodcutionPlans = DB::connection('odbc-connection-lx834f01')
                ->table('LX834F01.KFP')
                ->select('LX834F01.KFP.FPROD', 'LX834F01.KFP.FRDTE', 'LX834F01.KFP.FTYPE', 'LX834F01.KFP.FQTY', 'LX834F01.KFP.FCLAS', 'LX834F01.KFP.FDATE', 'LX834F01.KFP.FWHSE', 'LX834F01.KFP.FPCNO', 'LX834F01.IIM.IMPLC')
                ->join('LX834F01.IIM', 'LX834F01.IIM.IPROD', '=', 'LX834F01.KFP.FPROD')
                ->where(
                    [
                        ['LX834F01.KFP.FPROD', 'LIKE', $partNumber],
                        ['LX834F01.KFP.FTYPE', 'F'],
                        ['LX834F01.IIM.IMPLC', '!=', 'OBSOLETE']
                    ]
                )
                ->orderBy('LX834F01.KFP.FRDTE', 'DESC')
                ->get();

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
}
