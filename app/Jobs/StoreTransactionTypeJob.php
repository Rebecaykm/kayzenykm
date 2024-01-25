<?php

namespace App\Jobs;

use App\Models\TransactionType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreTransactionTypeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $type;
    private $description;

    /**
     * Create a new job instance.
     */
    public function __construct($type, $description)
    {
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        TransactionType::updateOrCreate(
            [
                'type' => $this->type,
            ],
            [
                'description' => $this->description,
            ],
        );
    }
}
