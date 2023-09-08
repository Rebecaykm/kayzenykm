<?php

namespace App\Jobs;

use App\Models\Workcenter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreWorkcenterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    private $number;
    private $name;
    private $departament_id;

    /**
     * Create a new job instance.
     */
    public function __construct($number, $name, $departament_id)
    {
        $this->number = $number;
        $this->name = $name;
        $this->departament_id = $departament_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Workcenter::create([
            'number' => $this->number,
            'name' => $this->name,
            'departament_id' => $this->departament_id
        ]);
    }
}
