<?php

namespace App\Jobs;

use App\Models\IPB;
use App\Models\Planner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PlannerMigrationJob implements ShouldQueue
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
        $planners = IPB::query()
            ->select('PBPBC', 'PBTYP', 'PBFAC', 'PBNAM')
            ->where([['PBTYP', 'LIKE', 'P'], ['PBFAC', '!=', ' ']])
            ->orderBy('PBPBC', 'ASC')->get();

        foreach ($planners as $key => $planner) {
            StorePlannerClassJob::dispatch(
                $planner->PBPBC,
                $planner->PBTYP,
                $planner->PBFAC,
                $planner->PBNAM
            );
        }
    }
}
