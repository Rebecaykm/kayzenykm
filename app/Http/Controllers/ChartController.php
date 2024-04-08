<?php

namespace App\Http\Controllers;

use App\Models\Departament;
use App\Models\ProdcutionRecord;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departaments = Departament::select('name')->get();

        $departamentCodes = Auth::user()->departaments->pluck('code')->toArray();
        $stationArray = ['111010', '122030', '138210'];

        $startWeek = Carbon::now()->startOfWeek()->format('Ymd H:i:s.v');
        $endWeek = Carbon::now()->endOfWeek()->format('Ymd H:i:s.v');

        $prodcutionRecords = ProdcutionRecord::select([
            'part_numbers.number AS noParte',
            'item_classes.abbreviation AS class',
            'workcenters.number AS noWorkCenter',
            'workcenters.name AS workCenter',
            'departaments.name AS departament',
            'production_plans.date AS datePlan',
            'shifts.name AS shiftPlan',
            'prodcution_records.quantity AS quantityProduced',
            'prodcution_records.sequence AS sequence',
            'statuses.name AS status',
            'prodcution_records.time_start AS HORA_INICIO',
            'prodcution_records.time_end AS HORA_FIN',
            'prodcution_records.minutes AS minutes',
            'users.name AS user',
            'prodcution_records.created_at AS dateRecorded'
        ])
            ->join('part_numbers', 'part_numbers.id', '=', 'prodcution_records.part_number_id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('item_classes', 'item_classes.id', '=', 'part_numbers.item_class_id')
            ->join('production_plans', 'production_plans.id', '=', 'prodcution_records.production_plan_id')
            ->join('shifts', 'shifts.id', '=', 'production_plans.shift_id')
            ->join('users', 'users.id', '=', 'prodcution_records.user_id')
            ->join('statuses', 'statuses.id', '=', 'prodcution_records.status_id')
            ->whereIn('departaments.code', $departamentCodes)
            ->whereIn('workcenters.number', $stationArray)
            ->whereBetween('prodcution_records.created_at', [$startWeek, $endWeek])
            ->orderBy('prodcution_records.created_at')
            ->get();


        $arrayProduction = [];

        foreach ($departaments as $departament) {
            $productionByDepartament = $prodcutionRecords->where('departament', $departament->name);

            $arrayProduction[$departament->name] = [];

            foreach ($productionByDepartament as $prodcutionRecord) {
                $noParte = $prodcutionRecord['noParte'];
                $quantityProduced = (float) $prodcutionRecord['quantityProduced']; // Convertir a flotante
                $dateRecorded = Carbon::parse($prodcutionRecord['dateRecorded']); // Obtener la fecha y hora completa

                // Redondear la hora al inicio de la hora
                $hourlyDateTime = $dateRecorded->copy()->startOfHour()->format('Y-m-d H:00:00');

                // Verificar si el número de parte ya existe en el array
                if (!isset($arrayProduction[$departament->name][$noParte][$hourlyDateTime])) {
                    // Inicializar el arreglo del número de parte para esa hora
                    $arrayProduction[$departament->name][$noParte][$hourlyDateTime] = 0;
                }

                // Sumar la cantidad producida para esa hora
                $arrayProduction[$departament->name][$noParte][$hourlyDateTime] += $quantityProduced;
            }
        }

        // Limpiar y reestructurar los datos
        $cleanedArrayProduction = [];

        foreach ($arrayProduction as $departament => $partData) {
            foreach ($partData as $partNumber => $hourlyData) {
                // Filtrar los datos para eliminar las horas sin producción
                $filteredHourlyData = array_filter($hourlyData);

                if (!empty($filteredHourlyData)) {
                    $cleanedArrayProduction[$departament][$partNumber] = [
                        'label' => array_keys($filteredHourlyData),
                        'data' => array_values($filteredHourlyData)
                    ];
                }
            }
        }

        return view('chart.index', compact('cleanedArrayProduction'));
    }

    public function productionPlanChart()
    {
        $departaments = Departament::select('name')->get();

        $departamentCodes = Auth::user()->departaments->pluck('code')->toArray();
        $stationArray = ['111010', '122030', '138210'];

        $startWeek = Carbon::now()->startOfWeek()->format('Ymd H:i:s.v');
        $endWeek = Carbon::now()->endOfWeek()->format('Ymd H:i:s.v');

        $productionPlans = ProductionPlan::select([
            'departaments.name AS name_departament',
            'workcenters.number AS number_workcenter',
            'workcenters.name AS name_workcenter',
            'part_numbers.number AS part_number',
            'production_plans.plan_quantity AS plan_quantity',
            'production_plans.production_quantity AS production_quantity',
            'production_plans.scrap_quantity AS scrap_quantity',
            'production_plans.date AS plan_date',
            'shifts.name AS shift',
            'statuses.name AS status',
            'production_plans.created_at AS created_at',
            'production_plans.created_at AS created_at'
        ])
            ->join('part_numbers', 'part_numbers.id', '=', 'production_plans.part_number_id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('item_classes', 'item_classes.id', '=', 'part_numbers.item_class_id')
            ->join('shifts', 'shifts.id', '=', 'production_plans.shift_id')
            ->join('statuses', 'statuses.id', '=', 'production_plans.status_id')
            // ->where('production_plans.status_id', '!=', 1)
            ->whereIn('departaments.code', $departamentCodes)
            ->whereIn('workcenters.number', $stationArray)
            ->whereBetween('production_plans.date', [$startWeek, $endWeek])
            ->orderBy('production_plans.date')
            ->orderBy('shifts.name')
            // ->orderBy('production_plans.updated_at')
            ->get();

        $arrayPlan = [];

        foreach ($productionPlans as $productionPlan) {
            $departament = $productionPlan->name_departament;
            $planDate = $productionPlan->plan_date;
            $shift = $productionPlan->shift;
            $partNumber = $productionPlan->part_number;
            $planQuantity = $productionPlan->plan_quantity;
            $productionQuantity = $productionPlan->production_quantity;

            if (!isset($arrayPlan[$departament][$planDate][$shift][$partNumber])) {
                $arrayPlan[$departament][$planDate][$shift][$partNumber] = [
                    'planQuantity' => $planQuantity,
                    'productionQuantity' => $productionQuantity
                ];
            }
        }

        return view('chart.production-plan-chart', compact('arrayPlan'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
