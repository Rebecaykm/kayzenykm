<?php

namespace App\Jobs;

use App\Models\ProductionPlan;
use App\Models\ScrapRecord;
use App\Models\YF020;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreYF020Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $productionPlan;

    /**
     * Create a new job instance.
     */
    public function __construct(ProductionPlan $productionPlan)
    {
        $this->productionPlan = $productionPlan;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $scrapRecords = ScrapRecord::query()->where([['flag', 0], ['production_plan_id', $this->productionPlan->id]])->orderBy('created_at', 'DESC')->get();

            if ($scrapRecords->count() > 0) {
                foreach ($scrapRecords as $key => $scrapRecord) {
                    $yf020 = YF020::query()->insert([
                        'YSWRKC' => $scrapRecord->partNumber->workcenter->number,
                        'YSWRKN' => $scrapRecord->partNumber->workcenter->name,
                        'YSRDTE' => Carbon::parse($this->productionPlan->date)->format('Ymd'),
                        'YSSHFT' => $this->productionPlan->shift->abbreviation,
                        'YSPPNO' => $this->productionPlan->productionRecords()->latest('sequence')->value('sequence') ?? '',
                        'YSPROD' => $scrapRecord->partNumber->number,
                        'YSQSCR' => $scrapRecord->quantity,
                        'YSSCRE' => $scrapRecord->scrap->code,
                        'YSCRDT' => Carbon::now()->format('Ymd'),
                        'YSCRTM' => Carbon::now()->format('His'),
                        'YSCRUS' => Auth::user()->infor ?? '',
                        //     'YSCRWS'=>,
                        //     'YSFIL1'=>,
                        //     'YSFIL2'=>,
                    ]);
                    $scrapRecord->update(['flag' => 1]);
                }
                // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
                // $query = "CALL LX834OU.YSF020C";
                // $result = odbc_exec($conn, $query);

                // if ($result) {
                //     Log::info("La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
                // } else {
                //     Log::info("Error en la consulta: " . odbc_errormsg($conn));
                // }

                // odbc_close($conn);
            }
        } catch (\Exception $e) {
            Log::error('Error en StoreYF020Job: ' . $e->getMessage());
        }
    }
}
