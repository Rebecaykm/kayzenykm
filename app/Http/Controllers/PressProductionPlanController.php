<?php

namespace App\Http\Controllers;

use App\Models\PressProductionPlan;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PressProductionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('part_number') ?? '';

        if (is_null($request->date)) {
            $startWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        } else {
            $startWeek = Carbon::parse($request->date)->startOfDay()->format('Y-m-d');
            $endWeek = Carbon::parse($request->date)->endOfDay()->format('Y-m-d');
        }

        $classArray = ['M1', 'M2', 'M3', 'M4'];

        $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
            return $line->workcenters->pluck('number')->all();
        });

        $statusIds = Status::whereIn('name', ['INACTIVO', 'CANCELADO'])->pluck('id')->toArray();

        // $pressProductionPlans = PressProductionPlan::with('workcenter', 'status', 'itemClass')
        //     ->whereNotIn('status_id', $statusIds)
        //     ->where(function ($query) use ($search) {
        //         $query->whereHas('partNumbers', function ($query) use ($search) {
        //             $query->where('number', 'LIKE', '%' . $search . '%');
        //         })->orWhereHas('workcenter', function ($query) use ($search) {
        //             $query->where('name', 'LIKE', '%' . $search . '%');
        //         });
        //     })
        //     ->whereIn('workcenter_number', $workcenterNumbers)
        //     ->whereIn('item_class_abbreviation', $classArray)
        //     ->whereBetween('plan_date', [$startWeek, $endWeek])
        //     ->orderBy('plan_date', 'asc')
        //     ->orderBy('shift_abbreviation', 'asc')
        //     ->orderBy('part_numbers.number')
        //     ->orderBy('workcenters.number', 'asc')
        //     ->paginate(10);

        $pressProductionPlans = PressProductionPlan::query()
            ->select([
                'press_production_plans.id as press_production_plans_id',
                'press_production_plans.plan_date',
                'press_production_plans.planned_hits',
                'press_production_plans.produced_hits',
                'press_production_plans.scrap_quantity',
                'press_part_numbers.id as part_number_id',
                'press_part_numbers.part_number as part_number',
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
            ->join('press_part_numbers', 'press_production_plans.press_part_number_id', '=', 'press_part_numbers.id')
            ->join('part_number_press_part_number', 'press_part_numbers.id', '=', 'part_number_press_part_number.press_part_number_id')
            ->join('part_numbers', 'part_number_press_part_number.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('shifts', 'press_production_plans.shift_id', '=', 'shifts.id')
            ->join('statuses', 'press_production_plans.status_id', '=', 'statuses.id')
            ->whereNotIn('press_production_plans.status_id', $statusIds)
            ->where(function ($query) use ($search) {
                $query->where('part_numbers.number', 'LIKE', '%' . $search . '%')
                    ->orWhere('workcenters.name', 'LIKE', '%' . $search . '%');
            })
            ->whereIn('workcenters.number', $workcenterNumbers)
            ->whereIn('item_classes.abbreviation', $classArray)
            ->whereBetween('press_production_plans.plan_date', [$startWeek, $endWeek])
            ->orderBy('press_production_plans.plan_date', 'asc')
            ->orderBy('shifts.abbreviation', 'asc')
            ->orderBy('part_numbers.number')
            ->orderBy('workcenters.number', 'asc')
            ->paginate(10);

        return view('pressProductionPlan.index', ['pressProductionPlans' => $pressProductionPlans]);
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
