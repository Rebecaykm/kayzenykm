<?php

namespace App\Jobs;

use App\Models\ItemClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreItemClassJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $abbreviation;
    private $name;

    /**
     * Create a new job instance.
     */
    public function __construct($abbreviation, $name)
    {
        $this->abbreviation = $abbreviation;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ItemClass::updateOrCreate(
            [
                'abbreviation' => $this->abbreviation,
            ],
            [
                'name' =>  $this->name,
            ],
        );
    }
}
