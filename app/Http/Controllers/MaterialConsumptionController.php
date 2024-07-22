<?php

namespace App\Http\Controllers;

use App\Jobs\CompletionProductionPlan;
use App\Models\MaterialConsumption;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaterialConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productionPlanId = $request->productionPlanId;

        return view('production-plan.press', ['productionPlanId' => $productionPlanId]);
    }

    /**
     *
     */
    public function spm(Request $request)
    {
        $productionPlan = ProductionPlan::findOrFail($request->productionPlanId);

        $productionPlan->update(['spm' => $request->quantitySmp]);

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        MaterialConsumption::create([
            'pack_number' => $request->materialCode,
            'production_plan_id' => $request->productionPlanId
        ]);

        return redirect()->back();
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

    /**
     *
     */
    public function finish(Request $request)
    {
        try {
            $productionPlan = ProductionPlan::findOrFail($request->productionPlanId);

            DB::transaction(function () use ($productionPlan) {
                CompletionProductionPlan::dispatch($productionPlan);
            });

            return redirect('production-plan')->with('success', 'La finalización de producción se ha realizado correctamente.');
        } catch (\Exception $e) {
            Log::error('MaterialConsumptionController: ' . $e->getMessage());

            return redirect('production-plan')->with('error', '¡Error! Hubo un problema durante el cierre de la Producción. Por favor, contactarse con el departamento de IT.');
        }
    }
}
