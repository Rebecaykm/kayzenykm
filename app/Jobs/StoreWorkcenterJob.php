<?php

namespace App\Jobs;

use App\Models\Departament;
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
    // private $departament;

    /**
     * Create a new job instance.
     */
    public function __construct($number, $name)
    {
        $this->number = $number;
        $this->name = $name;
        // $this->departament = $departament;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $departament = Departament::query()
        //     ->where('code', 'LIKE', $this->departament . '%')
        //     ->first();

        // if ($departament !== null) {
        //     Workcenter::updateOrCreate(
        //         [
        //             'number' => $this->number
        //         ],
        //         [
        //             'name' => $this->name,
        //             'departament_id' => $departament->id
        //         ]
        //     );
        // }

        Workcenter::updateOrCreate(
            [
                'number' => $this->number
            ],
            [
                'name' => $this->name
            ]
        );
    }
}
