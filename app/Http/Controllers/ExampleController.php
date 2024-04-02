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
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;



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
            $productionPlan = ProductionPlan::findOrFail($request->productionPlanId);
            $partNumberId = $request->partNumberId;
            $quantity = $request->quantity;
            $timeStart = Carbon::parse($request->timeStart);
            $timeEnd = Carbon::parse($request->timeEnd);
            $minutes = $timeEnd->diffInMinutes($timeStart);
            $partNumber = PartNumber::findOrFail($partNumberId);

            for ($count = 1; $quantity > 0; $count++) {

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
                    $productionPlan->id,
                    $currentQuantity,
                    $prodcutionRecordStatus->id
                );

                $dataArray[] = [
                    'id' => $result->id,
                    'departament' => strtoupper(trim($partNumber->workcenter->line->departament->name)),
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

    public function printipl(Request $request)
    {
        try {
            $productionPlan = ProductionPlan::findOrFail($request->productionPlanId);

            $x = $productionPlan->partNumber->workcenter->printer;

            $partNumberId = $request->partNumberId;
            $quantity = $request->quantity;
            $timeStart = Carbon::parse($request->timeStart);
            $timeEnd = Carbon::parse($request->timeEnd);

            $minutes = $timeEnd->diffInMinutes($timeStart);

            $partNumber = PartNumber::findOrFail($partNumberId);

            $models = implode(', ', $partNumber->projects->map(fn ($project) => $project->model)->all());

            for ($count = 1; $quantity > 0; $count++) {

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
                    $productionPlan->id,
                    $currentQuantity,
                    $prodcutionRecordStatus->id
                );

                try {
                    $connector = new NetworkPrintConnector($x->ip, $x->port);
                    $printer = new Printer($connector);
                    $command = '
                            R CW816 PF*
                            H1;f3;o558,22;c61;b0;h12;w14;d3,Departamento
                            H2;f3;o526,34;c61;b0;h20;w10;d3,' . strtoupper(trim($partNumber->workcenter->departament->name)) . '
                            L50;f0;o481,345;l100;w3
                            H3;f3;o558,350;c61;b0;h12;w14;d3,Estacion
                            H4;f3;o526,350;c61;b0;;h20;w10;d3,' . trim($partNumber->workcenter->name) . '
                            L51;f0;o481,580;l100;w3
                            H5;f3;o558,584;c61;b0;h12;w14;d3,Proyecto
                            H6;f3;o526,584;c61;b0;;h20;w10;d3,' . $models . '
                            L25;f1;o481,809;l787;w3
                            H7;f3;o480,22;c61;b0;;h20;w10;d3,Part number
                            H8;f3;o440,100;c68;b0;h26;w26;d3,' . trim($partNumber->number) . '
                            L26;f1;o381,809;l787;w3
                            H9;f3;o380,22;c61;b0;h12;w14;d3,Fecha de produccion
                            H10;f3;o350,22;c61;b0;h20;w10;d3,' . $productionPlan->date . '
                            L52;f0;o306,415;l80;w3
                            H11;f3;o380,420;c61;b0;h12;w14;d3,Consecutivo
                            H12;f3;o350,420;c61;b0;h20;w10;d3,' . $result->sequence . '
                            L27;f1;o306,809;l787;w3
                            H13;f3;o305,22;c61;b0;h12;w14;d3,Contenedor
                            H14;f3;o280,34;c61;b0;h20;w10;d3,.' . trim($partNumber->standardPackage->name) . '
                            L53;f0;o231,345;l80;w3
                            H15;f3;o305,350;c61;b0;h12;w14;d3,SNP
                            H16;f3;o280,350;c61;b0;;h20;w10;d3,' . $partNumber->quantity . '
                            L28;f1;o231,809;l787;w3
                            H29;f3;o230,22;c61;b0;h8;w14;d3,CANTIDAD PRODUCIDA
                            H17;f3;o220,42;c61;b0;h40;w14;d3,' . $currentQuantity . '
                            H18;f3;o200,22;c61;b0;;h20;w10;d3,
                            H19;f3;o120,22;c61;b0;h8;w14;d3,IDENTIFATION CARD
                            H20;f3;o100,22;c61;b0;;h10;w10;d3,"*** ORIGINAL ***"
                            H21;f3;o80,22;c61;b0;;h8;w10;d3,FECHA DE IMPRESION
                            H23;f3;o60,22;c61;b0;h10;w14;d3,' . now() . '
                            H22;f3;o20,22;c61;b0;h8;w14;d3,Y-TEC KEYLEX MEXICO
                            B24;o0,450;c17,200,0;w5;h5;d3,%SERIAL%%part_number%%estacion%
                            D0
                            R
                            l13
                            E*,1
                            1
                            1
                        ';
                    $printer->getPrintConnector()->write($command);
                    $printer->getPrintConnector()->finalize();
                } catch (\Exception $e) {
                    return "No se pudo establecer la conexión con la impresora: " . $e->getMessage();
                }

                $quantity -= $partNumber->quantity;
            }
            return redirect()->back();
        } catch (\Exception $e) {
            Log::emergency('ExampleController: ' . $e->getMessage());
        }
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
