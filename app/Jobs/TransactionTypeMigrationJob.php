<?php

namespace App\Jobs;

use App\Models\ITE;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransactionTypeMigrationJob implements ShouldQueue
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
        $transactions = ITE::query()->select('TTYPE', 'TDESC')->orderBy('TTYPE', 'ASC')->get();

        foreach ($transactions as $transaction) {
            StoreTransactionTypeJob::dispatch(
                $transaction->TTYPE,
                $transaction->TDESC
            );
        }
    }
}
