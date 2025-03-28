<?php

namespace App\Jobs;

use App\Models\FSO;
use App\Models\KFP;
use App\Models\PartNumber;
use Carbon\Carbon;
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

        $today = Carbon::now();

        $startDate = $today->copy()->format('Ymd');
        $endDate = $today->copy()->format('Ymd');

        $partNumbers = PartNumber::query()
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->whereIn('workcenters.id', [79, 143, 54, 294])
            ->pluck('part_numbers.number')
            ->toArray();

        $productionPlans = FSO::query()
            ->select(
                DB::raw('SPROD AS part_number'),
                'SQREQ as planned_quantity',
                DB::raw("VARCHAR(SUBSTR(SRDTE, 1, 4) || '-' || SUBSTR(SRDTE, 5, 2) || '-' || SUBSTR(SRDTE, 7, 2)) AS planned_date"),
                DB::raw("SUBSTR(TRIM(SOCNO), LENGTH(TRIM(SOCNO)), 1) AS planned_shift"),
            )
            ->whereIn(DB::raw('SPROD'), $partNumbers)
            ->whereBetween('SRDTE', [$startDate, $endDate])
            ->get();

        foreach ($productionPlans as $key => $productionPlan) {
            StoreProductionPlanJob::dispatch(
                $productionPlan->PART_NUMBER,
                $productionPlan->planned_quantity,
                $productionPlan->PLANNED_DATE,
                $productionPlan->PLANNED_SHIFT
            );
        }

        // $prodcutionPlans = KFP::query()->select('FPROD', 'FRDTE', 'FTYPE', 'FQTY', 'FCLAS', 'FDATE', 'FWHSE', 'FPCNO')->where('FTYPE', 'F')->orderBy('FRDTE', 'DESC')->get();

        // $prodcutionPlans = DB::connection('odbc-connection-lx834f02')
        //     ->table('LX834F01.KFP')
        //     ->select('LX834F01.KFP.FPROD', 'LX834F01.KFP.FRDTE', 'LX834F01.KFP.FTYPE', 'LX834F01.KFP.FQTY', 'LX834F01.KFP.FCLAS', 'LX834F01.KFP.FDATE', 'LX834F01.KFP.FWHSE', 'LX834F01.KFP.FPCNO', 'LX834F01.IIM.IMPLC')
        //     ->join('LX834F01.IIM', 'LX834F01.IIM.IPROD', '=', 'LX834F01.KFP.FPROD')
        //     ->where([['LX834F01.KFP.FTYPE', 'F'], ['LX834F01.IIM.IMPLC', '!=', 'OBSOLETE']])
        //     ->whereIn('LX834F01.IIM.ICLAS', ['M1', 'M2', 'M3', 'M4'])
        //     ->orderBy('LX834F01.KFP.FRDTE', 'DESC')
        //     ->get();


    }
}
