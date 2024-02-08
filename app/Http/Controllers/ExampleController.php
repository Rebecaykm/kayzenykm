<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Models\ProdcutionRecord;
use App\Models\ProductionPlan;
use App\Models\Status;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $productionPlanId = $request->productionPlanId;
            $partNumberId = $request->partNumberId;
            $quantity = $request->quantity;
            $timeStart = Carbon::parse($request->timeStart);
            $timeEnd = Carbon::parse($request->timeEnd);

            $minutes = $timeEnd->diffInMinutes($timeStart);

            $partNumber = PartNumber::findOrFail($partNumberId);

            for ($count = 1; $quantity > 0; $count++) {
                $productionPlan = ProductionPlan::findOrFail($productionPlanId);

                $prodcutionRecordStatus = ($productionPlan->plan_quantity > $productionPlan->production_quantity) ?
                    Status::where('name', 'DENTRO DE PLANEACIÓN')->first() :
                    Status::where('name', 'EXCEDENTE DE PLANEACIÓN')->first();

                $currentQuantity = min($quantity, $partNumber->quantity);

                $result = ProdcutionRecord::storeProductionRecord(
                    $partNumberId,
                    $currentQuantity,
                    $timeStart->format('Ymd H:i:s.v'),
                    $timeEnd->format('Ymd H:i:s.v'),
                    $minutes,
                    $productionPlanId,
                    $currentQuantity,
                    $prodcutionRecordStatus->id
                );

                $dataArray[] = [
                    'id' => $result->id,
                    'departament' => strtoupper(trim($partNumber->workcenter->departament->name)),
                    'workcenterNumber' => trim($partNumber->workcenter->number),
                    'workcenterName' => trim($partNumber->workcenter->name),
                    'partNumber' => trim($partNumber->number),
                    'quantity' => $currentQuantity,
                    'sequence' => $result->sequence,
                    'date' => $productionPlan->date,
                    'shift' => $productionPlan->shift->abbreviation,
                    'container' => trim($partNumber->standardPackage->name),
                    'snp' => $partNumber->quantity,
                    'production_plan_id' => $result->production_plan_id,
                    'user_id' => $result->user_id,
                    'projects' => $partNumber->projects,
                    'a' => "*** ORIGINAL ***"
                ];

                $quantity -= $partNumber->quantity;
            }

            $dataArrayWithQr = [];

            foreach ($dataArray as $key => $data) {
                $qrData = $data['id'] . ',' . $data['partNumber'] . ',' . $data['quantity'] . ',' . $data['sequence'] . ',' . Carbon::parse($data['date'])->format('Ymd') . ',' . $data['shift'];
                $qrCodeData = QrCode::size(600)->format('svg')->generate($qrData);
                $data['qrCode'] = $qrCodeData;

                $dataArrayWithQr[] = $data;
            }

            return View::make('label-example', ['dataArrayWithQr' => $dataArrayWithQr]);

        } catch (\Exception $e) {
            Log::emergency('ExampleController: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
