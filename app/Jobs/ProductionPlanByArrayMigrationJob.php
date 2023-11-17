<?php

namespace App\Jobs;

use App\Models\PartNumber;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

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
            $date = $item['date'];

            $startDate = Carbon::parse($date)->startOfWeek();
            $endDate = Carbon::parse($date)->endOfWeek();

            $planDelete = ProductionPlan::query()
                ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
                ->where('part_numbers.number', 'LIKE', $partNumber)
                ->whereBetween('production_plans.date', [$startDate, $endDate])
                ->delete();

            $prodcutionPlans = DB::connection('odbc-connection-lx834f02')
                ->table('LX834F02.KFP')
                ->select('LX834F02.KFP.FPROD', 'LX834F02.KFP.FRDTE', 'LX834F02.KFP.FTYPE', 'LX834F02.KFP.FQTY', 'LX834F02.KFP.FCLAS', 'LX834F02.KFP.FDATE', 'LX834F02.KFP.FWHSE', 'LX834F02.KFP.FPCNO', 'LX834F02.IIM.IMPLC')
                ->join('LX834F02.IIM', 'LX834F02.IIM.IPROD', '=', 'LX834F02.KFP.FPROD')
                ->where(
                    [
                        ['LX834F02.KFP.FPROD', 'LIKE', $partNumber],
                        ['LX834F02.KFP.FTYPE', 'F'],
                        ['LX834F02.IIM.IMPLC', '!=', 'OBSOLETE']
                    ]
                )
                ->orderBy('LX834F02.KFP.FRDTE', 'DESC')
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
