<?php

namespace App\Jobs;

use App\Models\YMCOM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PartHierarchyMigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // $parts = YMCOM::query()->select('MCFPRO', 'MCCPRO', 'MCQREQ')->orderBy('MCCCTM', 'ASC')->get();

        // foreach ($parts as $part) {
        //     StorePartHierarchyJob::dispatch(
        //         preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $part->MCFPRO),
        //         preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $part->MCCPRO),
        //         $part->MCQREQ
        //     );
        // }

        YMCOM::query()->select('MCFPRO', 'MCCPRO', 'MCQREQ')->orderBy('MCCCTM', 'ASC')
            ->chunk(200, function ($parts) {
                foreach ($parts as $part) {
                    StorePartHierarchyJob::dispatch(
                        preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $part->MCFPRO),
                        preg_replace('/[^a-zA-Z0-9\/\-\s]/', '', $part->MCCPRO),
                        $part->MCQREQ
                    );
                }
            });
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
