<?php

namespace App\Http\Controllers;

use App\Exports\ReportForecastFirmeExport;
use App\Models\PartNumber;
use App\Models\ProdcutionRecord;
use App\Models\ProductionPlan;
use App\Models\Status;
use App\Models\YK007;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
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
            DB::beginTransaction();

            $productionPlan = ProductionPlan::findOrFail($request->productionPlanId);
            $partNumberId = $request->partNumberId;
            $partNumber = PartNumber::findOrFail($partNumberId);
            $quantity = $request->quantity ?? $partNumber->quantity;
            $temp = $productionPlan->temp ?? 0;
            $timeStart = Carbon::parse($productionPlan->updated_at);
            $timeEnd = Carbon::now();
            $seconds = $timeEnd->diffInSeconds($timeStart) + $temp;
            $minutes = sprintf('%02d.%02d', floor($seconds / 60), $seconds % 60);

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
                    'print' => 1,
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
                $qrData = $data['print'] . ',' . $data['id'] . ',' . $data['partNumber'] . ',' . $data['quantity'] . ',' . $data['sequence'] . ',' . Carbon::parse($data['date'])->format('Ymd') . ',' . $data['shift'];
                $qrCodeData = QrCode::size(600)->format('svg')->generate($qrData);
                $data['qrCode'] = $qrCodeData;

                $dataArrayWithQr[] = $data;
            }

            $productionPlan->update(['temp' => 0]);

            DB::commit();

            return View::make('label-example', ['dataArrayWithQr' => $dataArrayWithQr]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::emergency('ExampleController: ' . $e->getMessage());
        }
    }

    /**
     *
     */
    public function printipl(Request $request)
    {
        try {
            DB::beginTransaction();

            $productionPlan = ProductionPlan::findOrFail($request->productionPlanId);

            $printer = $productionPlan->partNumber->workcenter->printer;

            $partNumberId = $request->partNumberId;
            $partNumber = PartNumber::findOrFail($partNumberId);
            $quantity = $request->quantity ?? $partNumber->quantity;
            $temp = $productionPlan->temp ?? 0;
            $timeStart = Carbon::parse($productionPlan->updated_at);
            $timeEnd = Carbon::now();
            $seconds = $timeEnd->diffInSeconds($timeStart) + $temp;
            $minutes = sprintf('%02d.%02d', floor($seconds / 60), $seconds % 60);

            $models = implode(', ', $partNumber->projects->map(fn($project) => $project->model)->all());

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
                    if ($printer->brand == 'ZEBRA') {
                        $codeQr = 1 . "-" . $result->id . '-' . trim($partNumber->number) . '-' . $currentQuantity . '-' . $result->sequence . '-' . Carbon::parse($productionPlan->date)->format('Ymd') . '-' . $productionPlan->shift->abbreviation;

                        $connector = new NetworkPrintConnector($printer->ip);

                        $printer = new Printer($connector);

                        $zplText = '^XA
                            ^FO480,50
                            ^GB120,400,2^FS
                            ^FO570,50
                            ^A0N,20,30
                            ^FWR
                            ^FDDepartamento:^FS
                            ^FO480,460
                            ^GB120,400,2^FS
                            ^FO570,500
                            ^A0N,20,30
                            ^FWr
                            ^FDEstación:^FS
                            ^FO480,870
                            ^GB120,300,2^FS
                            ^FO570,900
                            ^A0N,20,30
                            ^FWR
                            ^FDProyecto:^FS
                            ^FO500,50
                            ^A0N,50,50
                            ^FWR
                            ^FD' . strtoupper(trim($partNumber->workcenter->line->departament->name)) . '^FS
                            ^FO500,500
                            ^A0N,50,50
                            ^FWR
                            ^FD' . trim($partNumber->workcenter->name) . '^FS
                            ^FO500,900
                            ^A0N,50,50
                            ^FWR
                            ^FD' . $models . '^FS
                            ^FO350,50
                            ^GB120,1120,2^FS
                            ^FO450,50
                            ^A0N,20,30
                            ^FWR
                            ^FDNo. Parte:^FS
                            ^FO350,300
                            ^A0N,100,100
                            ^FWR
                            ^FD' . trim($partNumber->number) . '^FS
                            ^FO240,50
                            ^GB100,700,2^FS
                            ^FO310,300
                            ^A0N,20,30
                            ^FWR
                            ^FDFecha de Producción^FS
                            ^FO250,300
                            ^A0N,50,50
                            ^FWR
                            ^FD' . $productionPlan->date . '^FS
                            ^FO240,760
                            ^GB100,410,2^FS
                            ^FO310,800
                            ^A0N,20,30
                            ^FWR
                            ^FDSecuencia:^FS
                            ^FO250,800
                            ^A0N,50,50
                            ^FWR
                            ^FD' . $result->sequence . '^FS
                            ^FO130,50
                            ^GB100,400,2^FS
                            ^FO200,50
                            ^A0N,20,30
                            ^FWR
                            ^FDContenedor:^FS
                            ^FO130,460
                            ^GB100,400,2^FS
                            ^FO200,500
                            ^A0N,20,30
                            ^FWr
                            ^FDS N P:^FS
                            ^FO130,870
                            ^GB100,300,2^FS
                            ^FO200,900
                            ^A0N,20,30
                            ^FWR
                            ^FDCantidad Producida:^FS
                            ^FO100,50
                            ^A0N,20,30
                            ^FWR
                            ^FDIdentification Card^FS
                            ^FO50,50
                            ^A0N,20,30
                            ^FWR
                            ^FD*** ORIGINAL ***^FS
                            ^FO100,500
                            ^A0N,20,30
                            ^FWR
                            ^FDY-TEC KEYLEX MÉXICO^FS
                            ^FO60,500
                            ^A0N,20,30
                            ^FWR
                            ^FDFECHA DE IMPRESION^FS
                            ^FO30,500
                            ^A0N,20,30
                            ^FWR
                            ^Fd' . now() . '^FS
                            ^FO140,50
                            ^A0N,50,50
                            ^FWR
                            ^FD' . trim($partNumber->standardPackage->name) . '^FS
                            ^FO140,500
                            ^A0N,50,50
                            ^FWr
                            ^FD' . $partNumber->quantity . '^FS
                            ^FO140,950
                            ^A0N,50,50
                            ^FWR
                            ^FD' . $currentQuantity . '^FS
                            ^FO20,950
                            ^BQN,2,5
                            ^FDMM,' . $codeQr . '^FS
                            ^XZ';

                        $printer->textRaw($zplText);

                        $printer->close();
                    } else {
                        $connector = new NetworkPrintConnector($printer->ip, $printer->port);
                        $printer = new Printer($connector);
                        $command = '
                                R CW816 PF*
                                H1;f3;o558,22;c61;b0;h12;w14;d3,Departamento
                                H2;f3;o526,34;c61;b0;h20;w10;d3,' . strtoupper(trim($partNumber->workcenter->line->departament->name)) . '
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
                    }
                } catch (\Exception $e) {
                    Log::error('Error al imprimir etiqueta: ' . $e->getMessage());

                    DB::rollback();
                }

                $quantity -= $partNumber->quantity;
            }
            DB::commit();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('ExampleController.- Error en la impresión: ' . $e->getMessage());
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

    /**
     *
     */
    public function indexForecastFirm()
    {
        return view('forecast-firm.index');
    }

    /**
     *
     */
    public function reportPdf()
    {
        $data = YK007::query()->orderBy('DRSDT', 'ASC')->get();

        $sorPeriod = YK007::query()->select('DRSDT', 'DREDT')->where('DPROD', 'LIKE', '%-SOR%')->groupBy('DRSDT', 'DREDT')->get();

        $mpPeriod = YK007::query()->select('DRSDT', 'DREDT')->where('DPROD', 'NOT LIKE', '%-SOR%')->groupBy('DRSDT', 'DREDT')->get();

        $reportData = [];

        foreach ($data as $index => $item) {
            $reportData[] = [
                'no' => $index + 1,
                'part_no' => trim($item->DPROD),
                'forecast_quantity' => number_format($item->DRFQY, 1, '.', ''),
                'firm_order_quantity' => number_format($item->DROQY, 1, '.', ''),
                'defference_quantity' => number_format($item->DRDQY, 1, '.', ''),
                'defference_average' => $item->DRDRT,
            ];
        }

        $timestamp = now()->format('YmdHis');
        $filename = "{$timestamp}_Report Forecast vs Firm.pdf";

        $pdf = PDF::loadView('forecast-firm.report-pdf', ['reportData' => $reportData, 'sorPeriod' => $sorPeriod, 'mpPeriod' => $mpPeriod]);

        return $pdf->download($filename);
    }

    public function reportExcel()
    {
        $timestamp = now()->format('YmdHis');
        $filename = "{$timestamp}_Report Forecast vs Firm.xlsx";

        return Excel::download(new ReportForecastFirmeExport, $filename);
    }
}
