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
        // $workcenters = FRT::query()->select('RPROD', 'ROPNO', 'RWRKC', 'ROPDS')->orderBy('RPROD', 'ASC')->get();
        // foreach ($workcenters as $key => $workcenter) {
        //     $partNumer = PartNumber::query()->where('number', preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $workcenter->RPROD))->first();
        //     if ($partNumer !== null) {
        //         $wc = Workcenter::query()->where('number', preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $workcenter->RWRKC))->first();
        //         if ($wc !== null) {
        //             $partNumer->update(['workcenter_id' => $wc->id]);
        //         } else {
        //            Log::info('WorkCenter no encontrado : ' . $workcenter->RPROD . ', ' . $workcenter->RWRKC);
        //         }
        //     } else {
        //         Log::info('PartNumber no encontrado , ' . $workcenter->RPROD . ' , ' . $workcenter->RWRKC);
        //     }
        // }

        //

        FRT::query()
            ->select('RPROD', 'ROPNO', 'RWRKC', 'ROPDS')
            ->orderBy('RPROD', 'ASC')
            ->chunk(200, function ($workcenters) {
                foreach ($workcenters as $key => $workcenter) {
                    $partNumer = PartNumber::query()->where('number', preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $workcenter->RPROD))->first();
                    if ($partNumer !== null) {
                        $wc = Workcenter::query()->where('number', preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $workcenter->RWRKC))->first();
                        if ($wc !== null) {
                            $partNumer->update(['workcenter_id' => $wc->id]);
                        } else {
                            // Log::info('WorkCenter no encontrado : ' . $workcenter->RPROD . ', ' . $workcenter->RWRKC);
                        }
                    } else {
                        Log::info('PartNumber no encontrado , ' . $workcenter->RPROD . ' , ' . $workcenter->RWRKC);
                    }
                }
            });
    }
}
