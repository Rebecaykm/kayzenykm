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
            //     DB::transaction(function () use ($request) {
            $productionPlanId = $request->productionPlanId;
            $partNumberId = $request->partNumberId;
            $quantity = floor($request->quantity);
            $timeStart = Carbon::parse($request->timeStart);
            $timeEnd = Carbon::parse( $request->timeEnd);

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
                    'id' => str_pad($result->id, 6, '0', STR_PAD_LEFT),
                    'departament' => strtoupper(trim($partNumber->workcenter->departament->name)),
                    'workcenterNumber' => trim($partNumber->workcenter->number),
                    'workcenterName' => trim($partNumber->workcenter->name),
                    'partNumber' => trim($partNumber->number),
                    'quantity' => str_pad($currentQuantity, 6, '0', STR_PAD_LEFT),
                    'sequence' => $result->sequence,
                    'date' => $productionPlan->date,
                    'shift' => $productionPlan->shift->abbreviation,
                    'container' => trim($partNumber->standardPackage->name),
                    'snp' => str_pad($partNumber->quantity, 6, '0', STR_PAD_LEFT),
                    'production_plan_id' => str_pad($result->production_plan_id, 6, '0', STR_PAD_LEFT),
                    'user_id' => str_pad($result->user_id, 6, '0', STR_PAD_LEFT),
                    'projects' => $partNumber->projects,
                    'class' => $partNumber->itemClass->abbreviation,
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
            //     });
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
