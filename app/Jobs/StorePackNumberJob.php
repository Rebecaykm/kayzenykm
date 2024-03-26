<?php

namespace App\Jobs;

use App\Models\YHMIC;
use App\Models\YT4;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Return_;

class StorePackNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $YISINO, $YIPCNO, $YIPQTY, $YIPROD, $YIORDN, $YITORD;

    /**
     * Create a new job instance.
     */
    public function __construct($YISINO, $YIPCNO, $YIPQTY, $YIPROD, $YIORDN, $YITORD)
    {
        $this->YISINO = $YISINO;
        $this->YIPCNO = $YIPCNO;
        $this->YIPQTY = $YIPQTY;
        $this->YIPROD = $YIPROD;
        $this->YIORDN = $YIORDN;
        $this->YITORD = $YITORD;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            YT4::query()->insert([
                'Y4SINO' => $this->YISINO,
                'Y4TINO' => $this->YIPCNO,
                'Y4TQTY' => $this->YIPQTY,
                'Y4PROD' => $this->YIPROD,
                'Y4ORDN' => $this->YIORDN,
                'Y4TORD' => $this->YITORD,
                'Y4DAT' => Carbon::now()->format('Ymd'),
                'Y4TIM' => Carbon::now()->format('His'),
                'Y4USR' => Auth::user()->infor ?? '',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al procesar el trabajo StorePackNumberJob: ' . $e->getMessage());
        }
    }
}
