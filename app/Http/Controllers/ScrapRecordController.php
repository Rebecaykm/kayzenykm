<?php

namespace App\Http\Controllers;

use App\Models\ScrapRecord;
use App\Http\Requests\StoreScrapRecordRequest;
use App\Http\Requests\UpdateScrapRecordRequest;
use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Scrap;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScrapRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scrapRecords = ScrapRecord::query()->orderBy('created_at', 'DESC')->paginate(10);

        return view('scrap-record.index', ['scrapRecords' => $scrapRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productionPlan = ProductionPlan::findOrFail($request->production);
        $partNumber = PartNumber::findOrFail($request->item);
        $scraps = Scrap::query()->orderBy('code', 'ASC')->get();

        return view('scrap-record.create', ['productionPlan' => $productionPlan, 'partNumber' => $partNumber, 'scraps' => $scraps]);
    }

    public function createScrap()
    {
        dd("Si entro");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScrapRecordRequest $request)
    {
        $total = 0;

        ScrapRecord::create([
            'production_plan_id' => $request->production_plan_id,
            'part_number_id' => $request->part_number_id,
            'scrap_id' => $request->scrap_id,
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
        ]);

        $productionPlan = ProductionPlan::find($request->production_plan_id);
        $total =  $productionPlan->production_quantity + $request->quantity;
        $productionPlan->update(['production_quantity' => $total]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(ScrapRecord $scrapRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScrapRecord $scrapRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScrapRecordRequest $request, ScrapRecord $scrapRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScrapRecord $scrapRecord)
    {
        //
    }
}
