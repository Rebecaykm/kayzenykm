<?php

namespace App\Http\Controllers;

use App\Models\Departament;
use App\Models\ProdcutionRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departaments = Departament::select('name')->get();

        $departamentCodes = Auth::user()->departaments->pluck('code')->toArray();

        $startWeek = Carbon::now()->subWeek()->startOfWeek()->format('Ymd H:i:s.v');
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
            ->join('workcenters', 'workcenters.id', '=', 'part_numbers.workcenter_id')
            ->join('departaments', 'departaments.id', '=', 'workcenters.departament_id')
            ->join('item_classes', 'item_classes.id', '=', 'part_numbers.item_class_id')
            ->join('production_plans', 'production_plans.id', '=', 'prodcution_records.production_plan_id')
            ->join('shifts', 'shifts.id', '=', 'production_plans.shift_id')
            ->join('users', 'users.id', '=', 'prodcution_records.user_id')
            ->join('statuses', 'statuses.id', '=', 'prodcution_records.status_id')
            ->whereIn('departaments.code', $departamentCodes)
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
