<?php

namespace App\Jobs;

use App\Models\RYT4;
use App\Models\YHMIC;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckPackNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $packNumber;
    /**
     * Create a new job instance.
     */
    public function __construct($packNumber)
    {
        $this->packNumber = $packNumber;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $yhmicData = YHMIC::query()->where('YIPCNO', 'LIKE', $this->packNumber . '%')->get();

            if (!$yhmicData->isEmpty()) {
                foreach ($yhmicData as $key => $yhmic) {
                    $ryt4 = RYT4::where([
                        ['R4SINO', 'LIKE', $yhmic->YISINO . '%'],
                        ['R4TINO', 'LIKE', $yhmic->YIPCNO . '%'],
                        ['R4TQTY', 'LIKE', $yhmic->YIPQTY . '%'],
                        ['R4PROD', 'LIKE', $yhmic->YIPROD . '%'],
                        ['R4ORDN', 'LIKE', $yhmic->YIORDN . '%']
                    ])->first();

                    if (is_null($ryt4)) {
                        StorePackNumberJob::dispatch(
                            $yhmic->YISINO,
                            $yhmic->YIPCNO,
                            $yhmic->YIPQTY,
                            $yhmic->YIPROD,
                            $yhmic->YIORDN,
                            $yhmic->YITORD,
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error al procesar el trabajo CheckPackNumberJob: ' . $e->getMessage());
        }
    }
}
