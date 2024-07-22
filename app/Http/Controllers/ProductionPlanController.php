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
use Illuminate\Support\Str;
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
    public function destroy(ProductionPlan $productionPlan)
    {
    }

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
}
