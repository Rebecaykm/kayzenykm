<?php

namespace App\Jobs;

use App\Models\IPYF013;
use App\Models\ProdcutionRecord;
use App\Models\ProductionPlan;
use App\Models\ScrapRecord;
use App\Models\Status;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CompletionProductionPlan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

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
        $status = Status::where('name', 'INACTIVO')->first();

        $records = ProdcutionRecord::where('production_plan_id', $this->productionPlan->id)->get();

        $dateStart = Carbon::parse($records->min('created_at'))->format('Ymd');
        $dateEnd = Carbon::parse($records->max('created_at'))->format('Ymd');

        $timeStart = Carbon::parse($records->min('time_start'))->format('Hi');
        $timeEnd = Carbon::parse($records->max('time_end'))->format('Hi');

        if ($this->productionPlan->status->name != "INACTIVO" || $this->productionPlan->status_id != 7) {
            $check = IPYF013::query()->where([
                ['YFWRKC', $this->productionPlan->partNumber->workcenter->number],
                ['YFWRKN', $this->productionPlan->partNumber->workcenter->name],
                ['YFRDTE', Carbon::parse($this->productionPlan->date)->format('Ymd')],
                ['YFSHFT', $this->productionPlan->shift->abbreviation],
                ['YFPPNO', $this->productionPlan->productionRecords()->latest('sequence')->value('sequence')],
                ['YFPROD', $this->productionPlan->partNumber->number],
                // ['YFSTIM', $timeStart],
                // ['YFETIM', $timeEnd],
                // ['YFSDT', $dateStart . $timeStart],
                // ['YFEDT', $dateEnd . $timeEnd],
                ['YFQPLA', $this->productionPlan->plan_quantity],
                ['YFQPRO', $this->productionPlan->production_quantity],
                ['YFQSCR', $this->productionPlan->scrap_quantity],
            ])->first();

            if (is_null($check)) {
                StoreIPYF013Job::dispatch(
                    $this->productionPlan,
                    $timeStart,
                    $timeEnd,
                    $dateStart,
                    $dateEnd,
                );

                $scrapRecords = ScrapRecord::where('production_plan_id', $this->productionPlan->id)->get();

                foreach ($scrapRecords as $scrapRecord) {
                    $scrapRecord->update(['flag' => 1]);
                }

                $this->productionPlan->update(['status_id' => $status->id, 'production_end' => Carbon::now()->format('Ymd H:i:s.v')]);
            } else {
                Log::info("Registrado Anteriormente", ['data' => $this->productionPlan]);
            }
            // try {
            //     $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");

            //     if ($conn === false) {
            //         throw new Exception("Error al conectar con la base de datos Infor.");
            //     }

            //     $query = "CALL LX834OU.YSF013C";
            //     $result = odbc_exec($conn, $query);

            //     if ($result) {
            //         Log::info("LX834OU.YSF013C : La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
            //     } else {
            //         throw new Exception("LX834OU.YSF013C : Error en la consulta: " . odbc_errormsg($conn));
            //     }
            // } catch (Exception $e) {
            //     Log::alert($e->getMessage());
            // } finally {
            //     if (isset($conn)) {
            //         odbc_close($conn);
            //     }
            // }
        }
        else {
            Log::info("Estado Finalizado", ['data' => $this->productionPlan]);
        }
    }
}
