<?php

namespace App\Jobs;

use App\Models\Departament;
use App\Models\LWK;
use App\Models\Workcenter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WorkcenterMigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

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
        $workcenters = LWK::query()->select('WID', 'WWRKC', 'WDESC', 'WDEPT', 'WFORE')->orderBy('WWRKC', 'ASC')->get();


        foreach ($workcenters as $key => $workcenter) {
            StoreWorkcenterJob::dispatch(
                $workcenter->WWRKC,
                $workcenter->WDESC,
                substr($workcenter->WDEPT, 0, 2)
            );
        }
    }
}
