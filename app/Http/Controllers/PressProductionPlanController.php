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

        $pressProductionPlans = PressProductionPlan::with('workcenter', 'status', 'itemClass')
            ->whereNotIn('status_id', $statusIds)
            ->where(function ($query) use ($search) {
                $query->whereHas('partNumbers', function ($query) use ($search) {
                    $query->where('number', 'LIKE', '%' . $search . '%');
                })->orWhereHas('workcenter', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
            })
            ->whereIn('workcenter_number', $workcenterNumbers)
            ->whereIn('item_class_abbreviation', $classArray)
            ->whereBetween('plan_date', [$startWeek, $endWeek])
            ->orderBy('plan_date', 'asc')
            ->orderBy('shift_abbreviation', 'asc')
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
