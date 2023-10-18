<?php

namespace App\Http\Controllers;

use App\Models\ProdcutionRecord;
use App\Http\Requests\StoreProdcutionRecordRequest;
use App\Http\Requests\UpdateProdcutionRecordRequest;
use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdcutionRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodcutionRecords = ProdcutionRecord::orderBy('created_at', 'DESC')->orderBy('sequence', 'DESC')->paginate(10);

        return view('production-record.index', ['productionRecords' => $prodcutionRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productionPlan = ProductionPlan::findOrFail($request->production);

        return view('production-record.create', ['productionPlan' => $productionPlan]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdcutionRecordRequest $request)
    {
        $total = 0;
        $seq = 0;

        $start = new Carbon($request->time_start);
        $end = new Carbon($request->time_end);
        $minutes = $end->diffInMinutes($start);

        $count = 0;


        $snpQuantity = PartNumber::where('id', $request->part_number_id)->value('quantity');
        $quantity = $request->quantity;

        for ($count = 1; $quantity > 0; $count++) {

            $productionPlanQuantity = ProductionPlan::findOrFail($request->production_plan_id);

            if ($productionPlanQuantity->plan_quantity > $productionPlanQuantity->production_quantity) {
                $prodcutionRecordStatus = Status::where('name', 'DENTRO DE PLANEACIÓN')->first();
            } else {
                $prodcutionRecordStatus = Status::where('name', 'FUERA DE PLANEACIÓN')->first();
            }

            if ($quantity >= $snpQuantity) {
                ProdcutionRecord::storeProductionRecord($request->part_number_id, $snpQuantity, $request->time_start, $request->time_end, $minutes, $request->production_plan_id, $snpQuantity, $prodcutionRecordStatus->id);
                $quantity -= $snpQuantity;
            } else {
                ProdcutionRecord::storeProductionRecord($request->part_number_id, $quantity, $request->time_start, $request->time_end, $minutes, $request->production_plan_id, $quantity, $prodcutionRecordStatus->id);
                $quantity = 0;
            }
        }

        return redirect('production-plan');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdcutionRecord $prodcutionRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdcutionRecord $prodcutionRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdcutionRecordRequest $request, ProdcutionRecord $prodcutionRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdcutionRecord $prodcutionRecord)
    {
        //
    }
}
