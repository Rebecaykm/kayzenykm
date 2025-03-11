<?php

namespace App\Http\Controllers;

use App\Exports\ReporteExport;
use App\Models\ProductionPlan;
use App\Http\Requests\StoreProductionPlanRequest;
use App\Http\Requests\UpdateProductionPlanRequest;
use App\Imports\ProductionPlanImport;
use App\Jobs\CompletionProductionPlan;
use App\Jobs\ProductionPlanMigrationJob;
use App\Models\PartNumber;
use App\Models\Shift;
use App\Models\Status;
use App\Models\Workcenter;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductionPlanController extends Controller
{
    /**
     *
     */
    function dataUpload()
    {
        ProductionPlanMigrationJob::dispatch();

        return redirect('production-plan');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = strtoupper($request->part_number) ?? '';

        if (is_null($request->date)) {
            $startWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
            // $startWeek = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
            $endWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        } else {
            $startWeek = Carbon::parse($request->date)->format('Y-m-d');
            $endWeek = Carbon::parse($request->date)->format('Y-m-d');
        }

        $classArray = ['M1', 'M2', 'M3', 'M4'];

        $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
            return $line->workcenters->pluck('number')->all();
        });

        $statusIds = Status::whereIn('name', ['INACTIVO', 'CANCELADO'])->pluck('id')->toArray();

        $productionPlans = ProductionPlan::select([
            'production_plans.id as production_plan_id',
            'production_plans.date',
            'production_plans.plan_quantity',
            'production_plans.production_quantity',
            'production_plans.scrap_quantity',
            'part_numbers.id as part_number_id',
            'part_numbers.number as part_number',
            'item_classes.id as item_class_id',
            'workcenters.id as workcenter_id',
            'workcenters.name as workcenter_name',
            'lines.id as line_id',
            'departaments.id as departament_id',
            'shifts.id as shift_id',
            'shifts.abbreviation as shift_abbreviation',
            'statuses.id as status_id',
            'statuses.name as status_name'
        ])
            ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->join('statuses', 'production_plans.status_id', '=', 'statuses.id')
            ->whereNotIn('production_plans.status_id', $statusIds)
            ->where(function ($query) use ($search) {
                $query->where('part_numbers.number', 'LIKE', '%' . $search . '%')
                    ->orWhere('workcenters.name', 'LIKE', '%' . $search . '%');
            })
            ->whereIn('workcenters.number', $workcenterNumbers)
            ->whereIn('item_classes.abbreviation', $classArray)
            ->whereBetween('production_plans.date', [$startWeek, $endWeek])
            ->orderBy('production_plans.date', 'asc')
            ->orderBy('shifts.abbreviation', 'asc')
            ->orderBy('part_numbers.number')
            ->orderBy('workcenters.number', 'asc')
            ->paginate(10);

        return view('production-plan.index', ['productionPlans' => $productionPlans]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
            return $line->workcenters->pluck('number')->all();
        });

        $classArray = ['M1', 'M2', 'M3', 'M4'];

        $partNumbers = PartNumber::select(['part_numbers.number', 'part_numbers.id as part_number_id', 'workcenters.name as wc_name', 'part_numbers.quantity'])
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->where('obsolete', '!=', true)
            ->whereIn('item_classes.abbreviation', $classArray)
            ->whereIn('workcenters.number', $workcenterNumbers)
            ->orderBy('workcenters.name', 'asc')
            ->orderBy('part_numbers.number', 'asc')
            ->get();

        $shifts = Shift::orderBy('abbreviation', 'asc')->get();

        return view(
            'production-plan.create',
            ['parts' => $partNumbers, 'shifts' => $shifts]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionPlanRequest $request)
    {
        try {
            $productionPlan = ProductionPlan::create(
                [
                    'part_number_id' => $request->partNumber,
                    'plan_quantity' => $request->planQuantity,
                    'date' => Carbon::parse($request->date)->format('Y-m-d'),
                    'shift_id' => $request->shift,
                    'status_id' => 1
                ]
            );
            return redirect()->back()->with('success', '¡Registro exitoso! Se registró correctamente en el No. Parte ' . $productionPlan->partNumber->number);
        } catch (\Exception $e) {
            Log::error('ProductionPlanController - Error en el registro del plan de producción: ' . $e->getMessage());

            return redirect()->back()->with('error', '¡Error! Hubo un problema durante el registro del plan de producción. Por favor, revisa los detalles en los registros.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(ProductionPlan $productionPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionPlan $productionPlan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionPlanRequest $request, ProductionPlan $productionPlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionPlan $productionPlan) {}

    public function uploadFile(Request $request)
    {
        try {
            $file = $request->file('plan_file');

            Excel::import(new ProductionPlanImport, $file);

            $notFoundParts = session('not_found_parts', []);

            if (!empty($notFoundParts)) {
                $message = 'Los siguientes números de parte no se encontraron: ' . implode(', ', $notFoundParts);
                return redirect()->back()->with('warning', $message);
            }

            return redirect()->back()->with('success', 'Documento importado exitosamente.');
        } catch (ValidationException $e) {
            $failures = $e->failures();

            return redirect()->back()->withErrors($failures);
        } catch (\Exception $e) {
            Log::error('Error al importar el archivo: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Ocurrió un error al importar el archivo. Por favor, inténtelo de nuevo más tarde.');
        }
    }

    /**
     *
     */
    public function finish(Request $request)
    {
        try {
            $productionPlan = ProductionPlan::findOrFail($request->production);

            if ($productionPlan->production_quantity > 0 || $productionPlan->scrap_quantity > 0) {
                $allowedNames = ['estampado'];
                $departamentoName = Str::lower(optional($productionPlan->partNumber->workcenter->line->departament)->name);
                if (in_array($departamentoName, $allowedNames)) {
                    return redirect()->route('material-consumption.create', ['productionPlanId' => $productionPlan->id]);
                } else {
                    DB::transaction(function () use ($productionPlan) {
                        CompletionProductionPlan::dispatch($productionPlan);
                    });
                }
                return redirect('production-plan')->with('success', 'La finalización de producción se ha realizado correctamente.');
            } else {
                return redirect('production-plan')->with('error', '¡Error! No es posible finalizar la producción con valores en cero.');
            }
        } catch (\Exception $e) {
            Log::error('ProductionPlanController: ' . $e->getMessage());

            return redirect('production-plan')->with('error', '¡Error! Hubo un problema durante el cierre de la Producción. Por favor, contactarse con el departamento de IT.');
        }
    }

    public function loadToInfor()
    {
        try {
            $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");

            if ($conn === false) {
                throw new Exception("Error al conectar con la base de datos Infor.");
            }

            $query = "CALL LX834OU.YSF013C";
            $result = odbc_exec($conn, $query);

            if ($result) {
                Log::info("LX834OU.YSF013C : La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
            } else {
                throw new Exception("LX834OU.YSF013C : Error en la consulta: " . odbc_errormsg($conn));
            }
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        } finally {
            if (isset($conn)) {
                odbc_close($conn);
            }
        }
    }

    public function generarReporte()
    {
        // $workCenter = Workcenter::query()->where('name', 'MP11M')->first();
        // $shiftId = 1;
        // $date = '2025-02-04';

        // return Excel::download(new ReporteExport($workCenter->id, $shiftId, $date), 'forma_modificado.xlsx');

        // $filePath = storage_path('app/public/forma.xlsx');

        // $spreadsheet = IOFactory::load($filePath);
        // $sheet = $spreadsheet->getActiveSheet();

        // $productionPlans = ProductionPlan::query()
        //     ->select(
        //         'production_plans.id as id',
        //         'lines.name as lineName',
        //         'workcenters.name as workName',
        //         'part_numbers.number as partNumber',
        //         'production_plans.plan_quantity as planQuantity',
        //         'production_plans.production_quantity as productionQuantity',
        //         'production_plans.date as planDate',
        //         'shifts.name as shiftName'
        //     )
        //     ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
        //     ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
        //     ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
        //     ->join('lines', 'workcenters.line_id', '=', 'lines.id')
        //     ->where('production_plans.shift_id', $shiftId)
        //     ->where('production_plans.date', $date)
        //     ->where('workcenters.id', $workCenter->id)
        //     ->get();

        // $groupedResults = $productionPlans->groupBy(function ($item) {
        //     return $item->workName;
        // });

        // $finalResult = [];

        // foreach ($groupedResults as $workName => $workGroup) {
        //     $lineName = $workGroup->first()->lineName;
        //     $shiftName = $workGroup->first()->shiftName;
        //     $planDate = $workGroup->first()->planDate;

        //     $productionPlan = $workGroup->map(function ($item) {
        //         return [
        //             'partNumber' => $item->partNumber,
        //             'planQuantity' => $item->planQuantity,
        //             'productionQuantity' => $item->productionQuantity,
        //         ];
        //     });

        //     $finalResult[] = [
        //         'lineName' => $lineName,
        //         'workName' => $workName,
        //         'shiftName' => $shiftName,
        //         'planDate' => $planDate,
        //         'productionPlan' => $productionPlan,
        //     ];
        // }

        // foreach ($finalResult as $final) {

        //     $sheet->setCellValue('D9', $final['lineName']);
        //     $sheet->setCellValue('H9', $final['workName']);
        //     $sheet->setCellValue('H9', $final['shiftName']);
        //     $sheet->setCellValue('D11', $final['shiftName']);
        //     $sheet->setCellValue('H11', $final['planDate']);

        //     $tempFile = tempnam(sys_get_temp_dir(), 'excel') . '.xlsx';
        //     $writer = new Xlsx($spreadsheet);
        //     $writer->save($tempFile);

        //     return Response::download($tempFile, 'FORMA75_' . Carbon::now()->format('YmdHis') . '.xlsx')->deleteFileAfterSend(true);
        // }


        $workCenter = Workcenter::where('name', 'MK02 PW61')->first();
        $shiftId = 1;
        $date = '2025-02-10';

        $filePath = storage_path('app/public/forma.xlsx');

        // Cargar plantilla
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $productionPlans = ProductionPlan::query()
            ->select(
                'production_plans.id as id',
                'lines.name as lineName',
                'workcenters.name as workName',
                'part_numbers.number as partNumber',
                'production_plans.plan_quantity as planQuantity',
                'production_plans.production_quantity as productionQuantity',
                'production_plans.date as planDate',
                'shifts.name as shiftName'
            )
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->where('production_plans.shift_id', $shiftId)
            ->where('production_plans.date', $date)
            ->where('workcenters.id', $workCenter->id)
            ->get();

        // Agrupar resultados por estación de trabajo
        $groupedResults = $productionPlans->groupBy('workName');

        if ($groupedResults->isEmpty()) {
            return back()->with('error', 'No hay datos para generar el reporte.');
        }

        // Tomamos el primer grupo (si solo quieres generar un archivo para una estación de trabajo)
        $firstGroup = $groupedResults->first();

        // Extraer datos generales
        $lineName = $firstGroup->first()->lineName;
        $workName = $firstGroup->first()->workName;
        $shiftName = $firstGroup->first()->shiftName;
        $planDate = $firstGroup->first()->planDate;

        // Escribir datos en la cabecera del archivo
        $sheet->setCellValue('D9', $lineName);
        $sheet->setCellValue('H9', $workName);
        $sheet->setCellValue('D11', $shiftName);
        $sheet->setCellValue('H11', $planDate);

        // Escribir datos de producción en filas de la hoja de cálculo
        $startRow = 15; // Supongamos que los datos comienzan en la fila 14
        foreach ($firstGroup as $index => $plan) {
            $sheet->setCellValue("C" . ($startRow + $index), $plan->partNumber);
            $sheet->setCellValue("E" . ($startRow + $index), $plan->planQuantity);
            $sheet->setCellValue("M" . ($startRow + $index), $plan->productionQuantity);
        }

        // Crear el archivo en memoria
        $fileName = 'FORMA75_' . Carbon::now()->format('YmdHis') . '.xlsx';
        $tempFile = storage_path('app/public/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        return Response::download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
