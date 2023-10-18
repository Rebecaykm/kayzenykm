<?php

namespace App\Jobs;

use App\Models\KFP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // $prodcutionPlans = KFP::query()->select('FPROD', 'FRDTE', 'FTYPE', 'FQTY', 'FCLAS', 'FDATE', 'FWHSE', 'FPCNO')->where('FTYPE', 'F')->orderBy('FRDTE', 'DESC')->get();

        $prodcutionPlans = DB::connection('odbc-connection-lx834f01')
            ->table('LX834F01.KFP')
            ->select('LX834F01.KFP.FPROD', 'LX834F01.KFP.FRDTE', 'LX834F01.KFP.FTYPE', 'LX834F01.KFP.FQTY', 'LX834F01.KFP.FCLAS', 'LX834F01.KFP.FDATE', 'LX834F01.KFP.FWHSE', 'LX834F01.KFP.FPCNO', 'LX834F01.IIM.IMPLC')
            ->join('LX834F01.IIM', 'LX834F01.IIM.IPROD', '=', 'LX834F01.KFP.FPROD')
            ->where([['LX834F01.KFP.FTYPE', 'F'], ['LX834F01.IIM.IMPLC', '!=', 'OBSOLETE']])
            ->orderBy('LX834F01.KFP.FRDTE', 'DESC')
            ->get();

        foreach ($prodcutionPlans as $key => $prodcutionPlan) {
            // Log::info("$prodcutionPlan->FPROD, $prodcutionPlan->FQTY, $prodcutionPlan->FRDTE, $prodcutionPlan->FPCNO");
            StoreProductionPlanJob::dispatch(
                $prodcutionPlan->FPROD,
                $prodcutionPlan->FQTY,
                $prodcutionPlan->FRDTE,
                $prodcutionPlan->FPCNO
            );
        }
    }
}
