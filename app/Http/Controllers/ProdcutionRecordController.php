<?php

namespace App\Http\Controllers;

use App\Exports\ProdcutionRecordExport;
use App\Models\ProdcutionRecord;
use App\Http\Requests\StoreProdcutionRecordRequest;
use App\Http\Requests\UpdateProdcutionRecordRequest;
use App\Models\IPYF013;
use App\Models\LabelPrint;
use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\RYT4;
use App\Models\Status;
use App\Models\UnemploymentRecord;
use App\Models\User;
use App\Models\YHMIC;
use App\Models\YT4;
use Carbon\Carbon;

use Dompdf\Dompdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProdcutionRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
        //     return $line->workcenters->pluck('number')->all();
        // });

        $lineNames = Auth::user()->lines->pluck('name')->toArray();

        $prodcutionRecords = ProdcutionRecord::select([
            '*',
            'prodcution_records.id as prodcution_record_id',
            'production_plans.id as production_plan_id',
            'part_numbers.id as part_number_id',
            'item_classes.id as item_class_id',
            'workcenters.id as workcenter_id',
            'departaments.id as departament_id',
            'shifts.id as shift_id',
            'statuses.id as status_id',
            'prodcution_records.created_at',
            'prodcution_records.quantity as quantity_produced'
        ])
            ->join('part_numbers', 'prodcution_records.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('production_plans', 'prodcution_records.production_plan_id', '=', 'production_plans.id')
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->join('statuses', 'prodcution_records.status_id', '=', 'statuses.id')
            ->whereIn('lines.name', $lineNames)
            ->orderBy('prodcution_records.created_at', 'DESC')
            ->orderBy('prodcution_records.sequence', 'DESC')
            ->paginate(10);

        return view('production-record.index', ['productionRecords' => $prodcutionRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productionPlan = ProductionPlan::findOrFail($request->production);

        return view('production-record.create', ['productionPlan' => $productionPlan]);
    }

    /**
     *
     */
    public function startProduction(Request $request)
    {
        // Encontrar el plan de producción
        $productionPlan = ProductionPlan::findOrFail($request->productionPlananId);

        // Obtener los estados
        $statusProduccionDetenida = Status::where('name', 'PRODUCCIÓN DETENIDA')->firstOrFail();
        $statusEnProceso = Status::where('name', 'EN PROCESO')->firstOrFail();

        // Verificar el estado actual del plan de producción
        if ($productionPlan->status_id == $statusProduccionDetenida->id) {
            $user = User::with(['lines.unemployments'])->findOrFail(auth()->id());

            // Obtener las líneas y sus paros asociados
            $unemployments = $user->lines->flatMap(function ($line) {
                return $line->unemployments;
            })->unique('id')->values();

            // Obtener el estación de trabajo asociado
            $workcenter = $productionPlan->partNumber->workcenter;

            // Retornar la vista con los datos necesarios
            return view('production-record.unemployment', [
                'workcenter' => $workcenter,
                'unemployments' => $unemployments,
                'productionPlananId' => $productionPlan->id,
            ]);
        } else {
            // Actualizar el estado del plan de producción
            $productionPlan->update(['status_id' => $statusEnProceso->id, 'production_start' => Carbon::now()->format('Ymd H:i:s.v')]);

            // Redirigir de vuelta
            return redirect()->back();
        }
    }

    /**
     *
     */
    public function stopProduction(Request $request)
    {
        $productionPlan = ProductionPlan::find($request->productionPlananId);

        $temp = $productionPlan->temp ?? 0;

        $timeStart = Carbon::parse($productionPlan->updated_at);
        $timeEnd = Carbon::now();
        $seconds = $timeEnd->diffInSeconds($timeStart) + $temp;

        $statusProduccionDetenida = Status::where('name', 'PRODUCCIÓN DETENIDA')->firstOrFail();

        $productionPlan->update([
            'status_id' => $statusProduccionDetenida->id,
            'temp' => $seconds
        ]);

        return redirect('production-plan')->with('warning', 'Producción detenida con éxito');
    }

    /**
     *
     */
    public function cancelProduction(Request $request)
    {
        $statusProductionPlan = ProductionPlan::find($request->productionPlanId);

        $statusProduccionDetenida = Status::query()->where('name', 'CANCELADO')->first();

        $statusProductionPlan->update(['status_id' => $statusProduccionDetenida->id]);

        return redirect()->back()->with('error', 'Producción cancelada con éxito');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProdcutionRecord $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdcutionRecord $prodcutionRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdcutionRecord $prodcutionRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdcutionRecordRequest $request, ProdcutionRecord $prodcutionRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdcutionRecord $prodcutionRecord)
    {
        //
    }

    public function reprint(ProdcutionRecord $prodcutionRecord)
    {
        return view('production-record.reprint', ['prodcutionRecord' => $prodcutionRecord]);
    }

    /**
     *
     */
    public function storeRepint(Request $request)
    {
        $printReason = $request->printReason;
        $prodcutionRecordId = $request->prodcutionRecordId;

        $prodcutionRecord = ProdcutionRecord::findOrFail($prodcutionRecordId);

        $prodcutionRecord->update([
            'print_count' => $prodcutionRecord->print_count + 1,
        ]);

        LabelPrint::create([
            'print_count' => $prodcutionRecord->print_count,
            'print_reason' => $printReason,
            'prodcution_record_id' => $prodcutionRecordId,
            'user_id' => Auth::id()
        ]);

        $data = [
            'print' => $prodcutionRecord->print_count,
            'id' => $prodcutionRecord->id,
            'departament' => strtoupper(trim($prodcutionRecord->productionPlan->partNumber->workcenter->line->departament->name)),
            'workcenterNumber' => trim($prodcutionRecord->productionPlan->partNumber->workcenter->number),
            'workcenterName' => trim($prodcutionRecord->productionPlan->partNumber->workcenter->name),
            'partNumber' => trim($prodcutionRecord->productionPlan->partNumber->number),
            'quantity' => $prodcutionRecord->quantity,
            'sequence' => $prodcutionRecord->sequence,
            'date' => $prodcutionRecord->productionPlan->date,
            'shift' => $prodcutionRecord->productionPlan->shift->abbreviation,
            'container' => trim($prodcutionRecord->productionPlan->partNumber->standardPackage->name),
            'snp' => $prodcutionRecord->productionPlan->partNumber->quantity,
            'production_plan_id' => $prodcutionRecord->production_plan_id,
            'user_id' => $prodcutionRecord->user_id,
            'projects' => $prodcutionRecord->productionPlan->partNumber->projects,
            'a' => "*** REIMPRESIÓN ***"
        ];

        $qrData = $data['print'] . ',' . $data['id'] . ',' . $data['partNumber'] . ',' . $data['quantity'] . ',' . $data['sequence'] . ',' . Carbon::parse($data['date'])->format('Ymd') . ',' . $data['shift'];
        $qrCodeData = QrCode::size(600)->generate($qrData);
        $data['qrCode'] = $qrCodeData;

        $dataArrayWithQr[] = $data;

        return View::make('label-example', ['dataArrayWithQr' => $dataArrayWithQr]);
    }

    public function storeRepintIp(Request $request)
    {
        $printReason = $request->printReason;
        $prodcutionRecordId = $request->prodcutionRecordId;

        $prodcutionRecord = ProdcutionRecord::findOrFail($prodcutionRecordId);

        $prodcutionRecord->update([
            'print_count' => $prodcutionRecord->print_count + 1,
        ]);

        LabelPrint::create([
            'print_count' => $prodcutionRecord->print_count,
            'print_reason' => $printReason,
            'prodcution_record_id' => $prodcutionRecordId,
            'user_id' => Auth::id()
        ]);

        try {
            if ($prodcutionRecord->productionPlan->partNumber->workcenter->printer->brand == 'ZEBRA') {
                $codeQr = $prodcutionRecord->print_count . '-' . $prodcutionRecord->id . '-' . trim($prodcutionRecord->productionPlan->partNumber->number) . '-' . $prodcutionRecord->quantity . '-' . $prodcutionRecord->sequence . '-' . Carbon::parse($prodcutionRecord->productionPlan->date)->format('Ymd') . '-' . $prodcutionRecord->productionPlan->shift->abbreviation;

                $connector = new NetworkPrintConnector($prodcutionRecord->productionPlan->partNumber->workcenter->printer->ip);

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
                    ^FD' . strtoupper(trim($prodcutionRecord->productionPlan->partNumber->workcenter->line->departament->name)) . '^FS
                    ^FO500,500
                    ^A0N,50,50
                    ^FWR
                    ^FD' . trim($prodcutionRecord->productionPlan->partNumber->workcenter->name) . '^FS
                    ^FO500,900
                    ^A0N,50,50
                    ^FWR
                    ^FD' . $prodcutionRecord->productionPlan->partNumber->projects->pluck('model')->filter()->implode(', ') . '^FS
                    ^FO350,50
                    ^GB120,1120,2^FS
                    ^FO450,50
                    ^A0N,20,30
                    ^FWR
                    ^FDNo. Parte:^FS
                    ^FO350,300
                    ^A0N,100,100
                    ^FWR
                    ^FD' . trim($prodcutionRecord->productionPlan->partNumber->number) . '^FS
                    ^FO240,50
                    ^GB100,700,2^FS
                    ^FO310,300
                    ^A0N,20,30
                    ^FWR
                    ^FDFecha de Producción^FS
                    ^FO250,300
                    ^A0N,50,50
                    ^FWR
                    ^FD' . $prodcutionRecord->productionPlan->date . '^FS
                    ^FO240,760
                    ^GB100,410,2^FS
                    ^FO310,800
                    ^A0N,20,30
                    ^FWR
                    ^FDSecuencia:^FS
                    ^FO250,800
                    ^A0N,50,50
                    ^FWR
                    ^FD' . $prodcutionRecord->sequence . '^FS
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
                    ^FD' . trim($prodcutionRecord->productionPlan->partNumber->standardPackage->name) . '^FS
                    ^FO140,500
                    ^A0N,50,50
                    ^FWr
                    ^FD' . $prodcutionRecord->productionPlan->partNumber->quantity . '^FS
                    ^FO140,950
                    ^A0N,50,50
                    ^FWR
                    ^FD' . $prodcutionRecord->quantity . '^FS
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
    }

    /**
     *
     */
    public function report()
    {
        return view('production-record.report');
    }

    /**
     *
     */
    public function download(Request $request)
    {
        $validated = $request->validate(
            [
                'start' => ['required', 'date'],
                'end' => ['required', 'date', 'after:start'],
            ],
            [
                'start.required' => 'La fecha de inicio es necesaria.',
                'end.required' => 'La fecha final es necesaria.',
                'end.after' => 'La fecha final debe ser posterior a la fecha de inicio.',
            ]
        );

        $lineNames = Auth::user()->lines->pluck('name')->toArray();

        $start = Carbon::parse($request->start)->format('Ymd H:i:s.v');
        $end = Carbon::parse($request->end)->format('Ymd H:i:s.v');

        $prodcutionRecords = ProdcutionRecord::query()
            ->select([
                'departaments.name as name_departament',
                'lines.name as name_line',
                'workcenters.number as number_workcenter',
                'workcenters.name as name_workcenter',
                'part_numbers.number as number_part_number',
                'prodcution_records.sequence',
                'prodcution_records.quantity',
                'production_plans.date as date_production',
                'shifts.abbreviation as abbreviation_shift',
                'prodcution_records.time_start',
                'prodcution_records.time_end',
                'prodcution_records.minutes',
                'prodcution_records.created_at',
                'statuses.name as name_status',
            ])
            ->join('part_numbers', 'prodcution_records.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('statuses', 'prodcution_records.status_id', '=', 'statuses.id')
            ->join('production_plans', 'prodcution_records.production_plan_id', '=', 'production_plans.id')
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->whereBetween('prodcution_records.created_at', [$start, $end])
            ->whereIn('lines.name', $lineNames)
            ->orderBy('prodcution_records.created_at', 'DESC')
            ->orderBy('prodcution_records.sequence', 'DESC')
            ->get()
            ->toArray();

        return Excel::download(new ProdcutionRecordExport($prodcutionRecords), 'ProductionReport_' . date("dmYHis") . '.xlsx');
    }


    function cancel(ProdcutionRecord $prodcutionRecord)
    {
        $statusProductionPlan = $prodcutionRecord->productionPlan->status->name;

        $statusInactivo = Status::query()->where('name', 'INACTIVO')->value('name');
        $statusEnProceso = Status::query()->where('name', 'EN PROCESO')->value('name');
        $statusCancelado = Status::query()->where('name', 'CANCELADO')->first();

        if ($statusCancelado->name !== $prodcutionRecord->productionPlan->status->name) {

            if ($statusInactivo === $statusProductionPlan) {

                $productionPlan = $prodcutionRecord->productionPlan;

                $ipyf03 = IPYF013::query()->insert([
                    'YFWRKC' => $productionPlan->partNumber->workcenter->number,
                    'YFWRKN' => $productionPlan->partNumber->workcenter->name,
                    'YFRDTE' => Carbon::parse($productionPlan->date)->format('Ymd'),
                    'YFSHFT' => $productionPlan->shift->abbreviation,
                    'YFPPNO' => $prodcutionRecord->sequence,
                    'YFPROD' => $productionPlan->partNumber->number,
                    // 'YFSTIM' => $timeStart,
                    // 'YFETIM' => $timeEnd,
                    // 'YFSDT' => $dateStart . $timeStart,
                    // 'YFEDT' => $dateEnd . $timeEnd,
                    'YFQPLA' => $productionPlan->plan_quantity,
                    'YFQPRO' => -$prodcutionRecord->quantity,
                    // 'YFQSCR' => ,
                    // 'YFSCRE' => ,
                    'YFCRDT' => Carbon::now()->format('Ymd'),
                    'YFCRTM' => Carbon::now()->format('His'),
                    'YFCRUS' => Auth::user()->infor ?? '',
                    // 'YFCRWS' => ,
                    // 'YFFIL1' => ,
                    // 'YFFIL2' => ,
                ]);

                $currentQuantity = $prodcutionRecord->productionPlan->production_quantity;
                $newQuantity = $currentQuantity - $prodcutionRecord->quantity;

                $prodcutionRecord->productionPlan->update(['production_quantity' => $newQuantity]);
                $prodcutionRecord->update(['status_id' => $statusCancelado->id]);
            } elseif ($statusEnProceso === $statusProductionPlan) {

                $currentQuantity = $prodcutionRecord->productionPlan->production_quantity;
                $newQuantity = $currentQuantity - $prodcutionRecord->quantity;

                $prodcutionRecord->productionPlan->update(['production_quantity' => $newQuantity]);
                $prodcutionRecord->update(['status_id' => $statusCancelado->id]);
            } else {
                // :TODO
            }
        }
        return redirect()->back();
    }

    /**
     *
     */
    public function bais(Request $request)
    {
        $productionPlan = ProductionPlan::findOrFail($request->production);

        return view('production-record.bias', ['productionPlan' => $productionPlan]);
    }

    /**
     *
     */
    public function unemploymentProduction(Request $request)
    {
        // Encontrar el plan de producción
        $productionPlan = ProductionPlan::findOrFail($request->productionPlananId);

        // Calcular el tiempo
        $timeStart = Carbon::parse($productionPlan->updated_at);
        $timeEnd = Carbon::now();
        $seconds = $timeEnd->diffInSeconds($timeStart);
        $minutes = sprintf('%02d.%02d', floor($seconds / 60), $seconds % 60);

        // Crear el registro de paro
        UnemploymentRecord::create([
            'user_id' => Auth::id(),
            'workcenter_id' => $request->workcenterId,
            'unemployment_id' => $request->unemploymentId,
            'time_start' => $timeStart->format('Ymd H:i:s.v'),
            'time_end' => $timeEnd->format('Ymd H:i:s.v'),
            'minutes' => $minutes,
            'description' => $request->description ?? '',
            'reset_details' => $request->resetDetails ?? ''
        ]);

        // Actualizar el estado del plan de producción
        $statusEnProceso = Status::where('name', 'EN PROCESO')->firstOrFail();
        $productionPlan->update(['status_id' => $statusEnProceso->id]);

        // Redirigir a la ruta
        return redirect()->route('prodcution-record.create', ['production' => $productionPlan->id]);
    }

    /**
     *
     */
    public function back()
    {
        return redirect('prodcution-record');
    }
}
