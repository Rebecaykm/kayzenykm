<?php

namespace App\Jobs;

use App\Models\Planner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StorePlannerClassJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code;
    private $type;
    private $facility;
    private $name;

    /**
     * Create a new job instance.
     */
    public function __construct($code, $type, $facility, $name)
    {
        $this->code = $code;
        $this->type = $type;
        $this->facility = $facility;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Planner::updateOrCreate(
            [
                'code' => $this->code,
            ],
            [
                'type' => $this->type,
                'facility' => $this->facility,
                'name' =>  $this->name,
            ],
        );
    }
}
