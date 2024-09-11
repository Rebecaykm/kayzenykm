<?php

namespace App\Jobs;

use App\Models\IIM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PartNumberMigrationJob implements ShouldQueue
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
        $counter = 0;

        IIM::query()
            ->select('IDESC', 'IPROD', 'IOPB', 'IUMS', 'IITYP', 'ICLAS', 'IMSPKT', 'IMBOXQ', 'IBUYC', 'IREF04', 'IMENDT', 'IMENTM', 'IMPLC')
            ->orderBy('IMENDT', 'ASC')
            ->chunk(500, function ($partNumbers) use (&$counter) {
                foreach ($partNumbers as $key => $partNumber) {

                    $counter++;
                    Log::warning("PartNumberMigrationJob : $counter");

                    StorePartNumberJob::dispatch(
                        preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $partNumber->IDESC),
                        preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $partNumber->IPROD),
                        // $partNumber->IOPB,
                        $partNumber->IUMS,
                        $partNumber->IITYP,
                        $partNumber->ICLAS,
                        $partNumber->IMSPKT,
                        $partNumber->IMBOXQ,
                        $partNumber->IBUYC,
                        $partNumber->IREF04,
                        // $partNumber->IMENDT,
                        // $partNumber->IMENTM
                        trim($partNumber->IMPLC)
                    );
                }
            });
    }
}
