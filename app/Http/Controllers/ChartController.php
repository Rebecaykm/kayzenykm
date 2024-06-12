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
        // Obtener los nombres de las líneas del usuario autenticado
        $lineNames = Auth::user()->lines()->pluck('name')->toArray();

        // Obtener la fecha de inicio y fin de las últimas 12 horas
        $start = Carbon::now()->subDay()->format('Ymd H:i:s.v');
        $end = Carbon::now()->addHour()->format('Ymd H:i:s.v');

        // Obtener los registros de producción
        $productionRecords = ProdcutionRecord::select([
            'part_numbers.number AS noParte',
            'workcenters.number AS noWorkCenter',
            'workcenters.name AS workCenter',
            'lines.name AS lineName',
            'production_plans.date AS datePlan',
            'shifts.name AS shiftPlan',
            'prodcution_records.quantity AS quantityProduced',
            'prodcution_records.sequence AS sequence',
            'statuses.name AS status',
            'prodcution_records.time_start AS horaInicio',
            'prodcution_records.time_end AS horaFin',
            'prodcution_records.minutes AS minutes',
            'users.name AS user',
            'prodcution_records.created_at AS dateRecorded'
        ])
            ->join('part_numbers', 'part_numbers.id', '=', 'prodcution_records.part_number_id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('production_plans', 'production_plans.id', '=', 'prodcution_records.production_plan_id')
            ->join('shifts', 'shifts.id', '=', 'production_plans.shift_id')
            ->join('users', 'users.id', '=', 'prodcution_records.user_id')
            ->join('statuses', 'statuses.id', '=', 'prodcution_records.status_id')
            ->whereIn('lines.name', $lineNames)
            ->whereBetween('prodcution_records.created_at', [$start, $end])
            ->orderBy('prodcution_records.created_at')
            ->get();

        // Inicializar el arreglo para almacenar los datos de producción
        $arrayProduction = [];

        // Recorrer los registros de producción para procesar los datos
        foreach ($productionRecords as $record) {
            $lineName = $record->lineName;
            $workCenter = $record->workCenter;
            $noParte = $record->noParte;
            $quantityProduced = (float) $record->quantityProduced;
            $dateRecorded = Carbon::parse($record->dateRecorded);

            // Redondear la hora al inicio de la hora y formatear la fecha
            $hourlyDateTime = $dateRecorded->copy()->startOfHour()->format('d/m/y H:00');

            // Verificar si el número de parte ya existe en el array
            if (!isset($arrayProduction[$lineName][$workCenter][$noParte][$hourlyDateTime])) {
                // Inicializar el arreglo del número de parte para esa hora
                $arrayProduction[$lineName][$workCenter][$noParte][$hourlyDateTime] = 0;
            }

            // Sumar la cantidad producida para esa hora
            $arrayProduction[$lineName][$workCenter][$noParte][$hourlyDateTime] += $quantityProduced;
        }

        // Generar las últimas 12 horas
        $allTimes = [];
        $current = Carbon::now()->startOfHour();
        for ($i = 0; $i < 12; $i++) {
            $allTimes[] = $current->subHour()->format('d/m/y H:00');
        }
        $allTimes = array_reverse($allTimes);  // Revertimos para tener el orden correcto

        // Limpiar y reestructurar los datos
        $cleanedArrayProduction = [];

        foreach ($arrayProduction as $lineName => $workCenters) {
            foreach ($workCenters as $workCenter => $partData) {
                $cleanedArrayProduction[$lineName][$workCenter] = [
                    'labels' => $allTimes,
                    'datasets' => []
                ];

                foreach ($partData as $partNumber => $hourlyData) {
                    // Crear el dataset para el número de parte
                    $dataset = [
                        'label' => $partNumber,
                        'data' => [],
                        'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'fill' => false
                    ];

                    // Agregar las horas y los valores de producción
                    foreach ($allTimes as $hour) {
                        $dataset['data'][] = $hourlyData[$hour] ?? 0;
                    }

                    $cleanedArrayProduction[$lineName][$workCenter]['datasets'][] = $dataset;
                }
            }
        }

        return view('chart.index', compact('cleanedArrayProduction'));
    }




    public function productionPlanChart()
    {
        // Obtener los nombres de las líneas del usuario autenticado
        $lineNames = Auth::user()->lines()->pluck('name')->toArray();

        // Obtener la fecha de inicio y fin de la semana actual
        $startWeek = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $endWeek = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

        // Consulta optimizada
        $productionPlans = ProductionPlan::select([
            'lines.name AS name_line',
            'workcenters.number AS number_workcenter',
            'workcenters.name AS name_workcenter',
            'part_numbers.number AS part_number',
            'production_plans.plan_quantity AS plan_quantity',
            'production_plans.production_quantity AS production_quantity',
            'production_plans.scrap_quantity AS scrap_quantity',
            'production_plans.date AS plan_date',
            'shifts.name AS shift',
            'statuses.name AS status'
        ])
            ->join('part_numbers', 'part_numbers.id', '=', 'production_plans.part_number_id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('shifts', 'shifts.id', '=', 'production_plans.shift_id')
            ->join('statuses', 'statuses.id', '=', 'production_plans.status_id')
            ->whereIn('lines.name', $lineNames)
            ->whereBetween('production_plans.date', [$startWeek, $endWeek])
            ->orderBy('production_plans.date')
            ->orderBy('shifts.name')
            ->get();

        // Inicializar el arreglo para almacenar los datos de producción
        $arrayPlan = [];

        // Recorrer los planes de producción para procesar los datos
        foreach ($productionPlans as $productionPlan) {
            $line = $productionPlan->name_line;
            $planDate = $productionPlan->plan_date;
            $shift = $productionPlan->shift;
            $partNumber = $productionPlan->part_number;
            $planQuantity = $productionPlan->plan_quantity;
            $productionQuantity = $productionPlan->production_quantity;

            if (!isset($arrayPlan[$line][$planDate][$shift][$partNumber])) {
                $arrayPlan[$line][$planDate][$shift][$partNumber] = [
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
