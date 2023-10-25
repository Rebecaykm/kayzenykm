<?php

namespace App\Http\Controllers;

use App\Models\ProdcutionRecord;
use App\Http\Requests\StoreProdcutionRecordRequest;
use App\Http\Requests\UpdateProdcutionRecordRequest;
use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Status;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintBuffers\ImagePrintBuffer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProdcutionRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodcutionRecords = ProdcutionRecord::orderBy('created_at', 'DESC')->orderBy('sequence', 'DESC')->paginate(10);

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
        $pathToImage = public_path('images/label_image.png');

        $minutes = Carbon::parse($request->time_start)->diffInMinutes(Carbon::parse($request->time_end));

        // $partNumber = PartNumber::where('id', $request->part_number_id)->first();
        $partNumber = PartNumber::findOrFail($request->part_number_id);
        $quantity = $request->quantity;

        for ($count = 1; $quantity > 0; $count++) {

            $productionPlan = ProductionPlan::findOrFail($request->production_plan_id);

            $prodcutionRecordStatus = ($productionPlan->plan_quantity > $productionPlan->production_quantity) ?
                Status::where('name', 'DENTRO DE PLANEACIÓN')->first() :
                Status::where('name', 'FUERA DE PLANEACIÓN')->first();

            $currentQuantity = min($quantity, $partNumber->quantity);

            $result = ProdcutionRecord::storeProductionRecord($request->part_number_id, $currentQuantity, $request->time_start, $request->time_end, $minutes, $request->production_plan_id, $currentQuantity, $prodcutionRecordStatus->id);

            array_push($dataArray, [
                'id' => str_pad($result->id, 6, '0', STR_PAD_LEFT),
                'departament' => strtoupper(trim($partNumber->workcenter->departament->name)),
                'workcenterName' => trim($partNumber->workcenter->name),
                'partNumber' => trim($partNumber->number),
                'quantity' => str_pad($currentQuantity, 6, '0', STR_PAD_LEFT),
                'sequence' => $result->sequence,
                'date' => $productionPlan->date,
                'shift' => $productionPlan->shift->abbreviation,
                'container' => trim($partNumber->standardPackage->name),
                'snp' => str_pad($partNumber->quantity, 6, '0', STR_PAD_LEFT),
                'production_plan_id' => str_pad($result->production_plan_id, 6, '0', STR_PAD_LEFT),
                'user_id' => str_pad($result->user_id, 6, '0', STR_PAD_LEFT)
            ]);

            $quantity -= $partNumber->quantity;
        }
        //Etiqueta
        foreach ($dataArray as $data) {
            $qrData = $data['id'] . $data['partNumber'] . $data['quantity'] . $data['sequence'] . $data['date'] . $data['shift'];
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

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="etiqueta.pdf"',
        ]);

        // return redirect('production-plan');
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
}
