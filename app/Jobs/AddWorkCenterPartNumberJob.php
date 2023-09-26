<?php

namespace App\Jobs;

use App\Models\FRT;
use App\Models\PartNumber;
use App\Models\Workcenter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddWorkCenterPartNumberJob implements ShouldQueue
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
        $workcenters = FRT::query()->select('RPROD', 'ROPNO', 'RWRKC', 'ROPDS')->orderBy('RPROD', 'ASC')->get();
        foreach ($workcenters as $key => $workcenter) {
            $partNumer = PartNumber::query()->where('number', preg_replace('([^A-Za-z0-9])', '', $workcenter->RPROD))->first();
            if ($partNumer !== null) {
                $wc = Workcenter::query()->where('number', preg_replace('([^A-Za-z0-9])', '',$workcenter->RWRKC))->first();
                if ($wc !== null) {
                    $partNumer->update(['workcenter_id' => $wc->id]);
                } else {
                    Log::info('WorkCenter: ' . $workcenter->RPROD . ', ' . $workcenter->RWRKC);
                }
            } else {
                Log::info('PartNumber: ' . $workcenter->RPROD . ', ' . $workcenter->RWRKC);
            }
        }
    }
}
