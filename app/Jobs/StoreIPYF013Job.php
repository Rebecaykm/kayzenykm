<?php

namespace App\Jobs;

use App\Models\IPYF013;
use App\Models\ProductionPlan;
use App\Models\ScrapRecord;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreIPYF013Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $productionPlan;
    private $timeStart;
    private $timeEnd;
    private $dateStart;
    private $dateEnd;

    /**
     * Create a new job instance.
     */
    public function __construct(
        ProductionPlan $productionPlan,
        $timeStart,
        $timeEnd,
        $dateStart,
        $dateEnd,
    ) {
        $this->productionPlan = $productionPlan;
        $this->timeStart = $timeStart;
        $this->timeEnd = $timeEnd;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            if ($this->productionPlan->scrap_quantity > 0) {
                $maxQuantityScrapRecord = ScrapRecord::query()
                    ->where('production_plan_id', $this->productionPlan->id)
                    ->orderByDesc('quantity')
                    ->orderByDesc('created_at')
                    ->first();
            }

            IPYF013::query()->insert([
                'YFWRKC' => $this->productionPlan->partNumber->workcenter->number ?? '',
                'YFWRKN' => $this->productionPlan->partNumber->workcenter->name ?? '',
                'YFRDTE' => Carbon::parse($this->productionPlan->date)->format('Ymd') ?? '',
                'YFSHFT' => $this->productionPlan->shift->abbreviation ?? '',
                'YFPPNO' => $this->productionPlan->productionRecords()->latest('sequence')->value('sequence') ?? '',
                'YFPROD' => $this->productionPlan->partNumber->number ?? '',
                'YFSTIM' => $this->timeStart ?? '',
                'YFETIM' => $this->timeEnd ?? '',
                'YFSDT' => $this->dateStart . $this->timeStart ?? '',
                'YFEDT' => $this->dateEnd . $this->timeEnd ?? '',
                'YFQPLA' => $this->productionPlan->plan_quantity ?? '',
                'YFQPRO' => $this->productionPlan->production_quantity ?? '',
                'YFQSCR' => $this->productionPlan->scrap_quantity ?? '',
                'YFSCRE' => $maxQuantityScrapRecord->scrap->code ?? '',
                'YFCRDT' => Carbon::now()->format('Ymd') ?? '',
                'YFCRTM' => Carbon::now()->format('His') ?? '',
                'YFCRUS' => Auth::user()->infor ?? '',
                // 'YFCRWS' => ,
                // 'YFFIL1' => ,
                // 'YFFIL2' => ,
            ]);

            // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
            // $query = "CALL LX834OU.YSF013B";
            // $result = odbc_exec($conn, $query);

            // if ($result) {
            //     Log::info("La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
            // } else {
            //     Log::info("Error en la consulta: " . odbc_errormsg($conn));
            // }

            // odbc_close($conn);
        } catch (\Exception $e) {
            Log::error('Error en StoreIPYF013Job: ' . $e->getMessage());
        }
    }
}
