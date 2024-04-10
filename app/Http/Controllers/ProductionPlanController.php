<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan;
use App\Http\Requests\StoreProductionPlanRequest;
use App\Http\Requests\UpdateProductionPlanRequest;
use App\Imports\ProductionPlanImport;
use App\Jobs\CompletionProductionPlan;
use App\Jobs\ProductionPlanMigrationJob;
use App\Models\PartNumber;
use App\Models\Shift;
use App\Models\Status;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

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
        $search = strtoupper($request->search) ?? '';

        $startWeek = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
        $endWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        // $classArray = ['M1', 'M2', 'M3', 'M4'];
        $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
            return $line->workcenters->pluck('number')->all();
        });

        $status = Status::where('name', 'INACTIVO')->first();

        $productionPlans = ProductionPlan::select([
            '*',
            'production_plans.id as production_plan_id',
            'part_numbers.id as part_number_id',
            'item_classes.id as item_class_id',
            'workcenters.id as workcenter_id',
            'lines.id as line_id',
            'departaments.id as departament_id',
            'shifts.id as shift_id',
            'statuses.id as status_id'
        ])
            ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->join('statuses', 'production_plans.status_id', '=', 'statuses.id')
            ->where('production_plans.status_id', '!=', $status->id)
            ->where(function ($query) use ($search) {
                $query->where('part_numbers.number', 'LIKE', '%' . $search . '%')
                    ->orWhere('workcenters.name', 'LIKE', '%' . $search . '%');
            })
            ->whereIn('workcenters.number', $workcenterNumbers)
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

        $partNumbers = PartNumber::select(['part_numbers.number', 'part_numbers.id as part_number_id', 'workcenters.name as wc_name'])
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
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
    public function destroy(ProductionPlan $productionPlan)
    {
    }

    public function uploadFile(Request $request)
    {
        try {
            $file = $request->file('plan_file');

            Excel::import(new ProductionPlanImport, $file);

            return redirect()->back()->with('success', 'Documento Importado Exitosamente');
        } catch (ValidationException $e) {
            $failures = $e->failures();

            return redirect()->back()->withErrors($failures);
        } catch (\Exception $e) {
            Log::error('Error al importar el archivo: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Ocurrió un error al importar el archivo. Por favor, inténtelo de nuevo más tarde.');
        }
    }

    public function finish(Request $request)
    {
        try {
            $productionPlan = ProductionPlan::findOrFail($request->production);

            if ($productionPlan->production_quantity > 0 || $productionPlan->scrap_quantity > 0) {
                DB::transaction(function () use ($productionPlan) {
                    CompletionProductionPlan::dispatch($productionPlan);
                });
                return redirect('production-plan')->with('success', 'La finalización de producción se ha realizado correctamente.');
                // return redirect()->back()->with('success', 'La finalización de producción se ha realizado correctamente.');
            } else {
                return redirect('production-plan')->with('error', '¡Error! No es posible finalizar la producción con valores en cero.');
                // return redirect()->back()->with('error', '¡Error! No es posible finalizar la producción con valores en cero.');
            }
        } catch (\Exception $e) {
            Log::error('ProductionPlanController: ' . $e->getMessage());

            return redirect('production-plan')->with('error', '¡Error! Hubo un problema durante el cierre de la Producción. Por favor, contactarse con el departamento de IT.');
            // return redirect()->back()->with('error', '¡Error! Hubo un problema durante el cierre de la Producción. Por favor, contactarse con el departamento de IT.');
        }
    }

    public function loadToInfor()
    {
        try {
            $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");

            if ($conn === false) {
                throw new Exception("Error al conectar con la base de datos Infor.");
            }

            $query = "CALL LX834OU02.YSF013C";
            $result = odbc_exec($conn, $query);

            if ($result) {
                Log::info("LX834OU02.YSF013C : La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
            } else {
                throw new Exception("LX834OU02.YSF013C : Error en la consulta: " . odbc_errormsg($conn));
            }
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        } finally {
            if (isset($conn)) {
                odbc_close($conn);
            }
        }
    }
}
