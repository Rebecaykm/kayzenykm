<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan;
use App\Http\Requests\StoreProductionPlanRequest;
use App\Http\Requests\UpdateProductionPlanRequest;
use App\Jobs\ProductionPlanMigrationJob;
use App\Models\Unemployment;
use App\Models\Workcenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $search = $request->search ?? '';

        $startWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $arrayClass = ['M1', 'M2', 'M3', 'M4'];
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $productionPlans =  ProductionPlan::whereHas('partNumber.itemClass', function ($query) use ($arrayClass) {
            $query->whereIn('abbreviation', $arrayClass);
        })
            ->whereHas('partNumber.workcenter.departament', function ($query) use ($departamentCode) {
                $query->whereIn('code', $departamentCode);
            })
            // ->whereBetween('date', [$startWeek, $endWeek])
            ->where('part_numbers.number', 'LIKE', '%' . $search . '%')
            ->where('status', true)
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->orderBy('production_plans.date', 'asc')
            ->orderBy('shifts.abbreviation', 'asc')
            ->orderBy('workcenters.number', 'asc')
            ->paginate(10);

        return view('production-plan.index', ['productionPlans' => $productionPlans]);
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
    public function store(StoreProductionPlanRequest $request)
    {
        //
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
        //
    }

    function productionUnemploymentCreate()
    {
        $user = Auth::user()->departaments->pluck('id')->toArray();

        $workcenters = Workcenter::whereHas('departament', function ($query) use ($user) {
            $query->whereIn('departaments.id', $user);
        })
        ->orderBy('number', 'asc')
        ->get();

        $unemployments = Unemployment::orderBy('name', 'asc')->get();

        return view('production-plan.production-unemployment-create', ['workcenters' => $workcenters, 'unemployments' => $unemployments]);
    }

    function productionUnemploymentStore()
    {
    }
}
