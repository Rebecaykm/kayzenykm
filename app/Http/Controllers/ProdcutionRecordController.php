<?php

namespace App\Http\Controllers;

use App\Exports\ProdcutionRecordExport;
use App\Models\ProdcutionRecord;
use App\Http\Requests\StoreProdcutionRecordRequest;
use App\Http\Requests\UpdateProdcutionRecordRequest;
use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Status;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProdcutionRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $prodcutionRecords = ProdcutionRecord::select([
            '*',
            'prodcution_records.id as prodcution_record_id',
            'production_plans.id as production_plan_id',
            'part_numbers.id as part_number_id',
            'item_classes.id as item_class_id',
            'workcenters.id as workcenter_id',
            'departaments.id as departament_id',
            'shifts.id as shift_id',
            'statuses.id as status_id'
        ])
            ->join('part_numbers', 'prodcution_records.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->join('production_plans', 'prodcution_records.production_plan_id', '=', 'production_plans.id')
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->join('statuses', 'prodcution_records.status_id', '=', 'statuses.id')
            ->whereIn('departaments.code', $departamentCode)
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
     * Store a newly created resource in storage.
     */
    public function store(StoreProdcutionRecordRequest $request)
    {
        $dataArray = [];
        $htmlContent = '';

        $minutes = Carbon::parse($request->time_start)->diffInMinutes(Carbon::parse($request->time_end));

        $partNumber = PartNumber::findOrFail($request->part_number_id);
        $quantity = $request->quantity;

        for ($count = 1; $quantity > 0; $count++) {

            $productionPlan = ProductionPlan::findOrFail($request->production_plan_id);

            $prodcutionRecordStatus = ($productionPlan->plan_quantity > $productionPlan->production_quantity) ?
                Status::where('name', 'DENTRO DE PLANEACIÓN')->first() :
                Status::where('name', 'EXCEDENTE DE PLANEACIÓN')->first();

            $currentQuantity = min($quantity, $partNumber->quantity);

            $result = ProdcutionRecord::storeProductionRecord(
                $request->part_number_id,
                $currentQuantity,
                $request->time_start,
                $request->time_end,
                $minutes,
                $request->production_plan_id,
                $currentQuantity,
                $prodcutionRecordStatus->id
            );

            array_push($dataArray, [
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
            ]);

            $quantity -= $partNumber->quantity;
        }

        foreach ($dataArray as $data) {
            $qrData = $data['id'] . $data['partNumber'] . $data['quantity'] . $data['sequence'] . Carbon::parse($data['date'])->format('Ymd') . $data['shift'];
            $qrCodeData = QrCode::size(600)->generate($qrData);
            $data['qrCode'] = $qrCodeData;
            $view = View::make('label', $data);
            $htmlContent .= $view->render();

            // $htmlContent = $view->render();
            // $connector = new WindowsPrintConnector("EPSON TM-T20 Receipt");
            // $printer = new Printer($connector);
            // $printer->text($htmlContent);
            // $printer->cut();
            // $printer->close();
        }

        $pdf = new Dompdf();
        $pdf->loadHtml($htmlContent);
        $pdf->setPaper(array(0, 0, 216, 432), 'portrait');
        $pdf->render();

        $output = $pdf->output();

        // return response($output, 200, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="etiqueta.pdf"',
        // ]);

        session()->put('pdfData', base64_encode($output));

        return redirect()->back();
    }

    public function clearPDFSessionData()
    {
        session()->forget('pdfData');
        return response()->json(['message' => 'PDF session data cleared successfully']);
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
        $data = [
            'id' => str_pad($prodcutionRecord->id, 6, '0', STR_PAD_LEFT),
            'departament' => strtoupper(trim($prodcutionRecord->productionPlan->PartNumber->workcenter->departament->name)),
            'workcenterNumber' => trim($prodcutionRecord->productionPlan->PartNumber->workcenter->number),
            'workcenterName' => trim($prodcutionRecord->productionPlan->PartNumber->workcenter->name),
            'partNumber' => trim($prodcutionRecord->productionPlan->PartNumber->number),
            'quantity' => str_pad($prodcutionRecord->quantity, 6, '0', STR_PAD_LEFT),
            'sequence' => $prodcutionRecord->sequence,
            'date' => $prodcutionRecord->productionPlan->date,
            'shift' => $prodcutionRecord->productionPlan->shift->abbreviation,
            'container' => trim($prodcutionRecord->productionPlan->partNumber->standardPackage->name),
            'snp' => str_pad($prodcutionRecord->productionPlan->partNumber->quantity, 6, '0', STR_PAD_LEFT),
            'production_plan_id' => str_pad($prodcutionRecord->production_plan_id, 6, '0', STR_PAD_LEFT),
            'user_id' => str_pad($prodcutionRecord->user_id, 6, '0', STR_PAD_LEFT),
            'projects' => $prodcutionRecord->productionPlan->partNumber->projects,
            'class' => $prodcutionRecord->productionPlan->partNumber->itemClass->abbreviation,
            'a' => "*** REIMPRESIÓN ***"
        ];

        $qrData = $data['id'] . $data['partNumber'] . $data['quantity'] . $data['sequence'] . Carbon::parse($data['date'])->format('Ymd') . $data['shift'];
        $qrCodeData = QrCode::size(600)->generate($qrData);
        $data['qrCode'] = $qrCodeData;
        $view = View::make('label', $data);
        $htmlContent = $view->render();

        $pdf = new Dompdf();
        $pdf->loadHtml($htmlContent);
        $pdf->setPaper(array(0, 0, 216, 432), 'portrait');
        $pdf->render();

        $output = $pdf->output();

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="etiqueta.pdf"',
        ]);
    }

    public function report()
    {
        return view('production-record.report');
    }

    public function download(Request $request)
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->end)->format('Y-m-d H:i:s');

        $prodcutionRecords = ProdcutionRecord::query()
            ->select(
                'departaments.name as name_departament',
                'workcenters.number as number_workcenter',
                'workcenters.name as name_workcenter',
                'part_numbers.number as number_part_number',
                'prodcution_records.sequence',
                'prodcution_records.quantity',
                'production_plans.date as date_production',
                'shifts.abbreviation as abbreviation_shift',
                'prodcution_records.time_start',
                'prodcution_records.time_end',
                // 'prodcution_records.minutes',
                'prodcution_records.created_at',
                'statuses.name as name_status',
            )
            ->join('part_numbers', 'prodcution_records.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->join('statuses', 'prodcution_records.status_id', '=', 'statuses.id')
            ->join('production_plans', 'prodcution_records.production_plan_id', '=', 'production_plans.id')
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->whereBetween('prodcution_records.created_at', [$start, $end])
            ->whereIn('departaments.code', $departamentCode)
            ->orderBy('prodcution_records.created_at', 'DESC')
            ->orderBy('prodcution_records.sequence', 'DESC')
            ->get()
            ->toArray();

        return Excel::download(new ProdcutionRecordExport($prodcutionRecords), 'ProductionReport_' . date("dmY") . '.xlsx');
    }
}
