<?php

namespace App\Http\Controllers;

use App\Exports\ScrapRecordExport;
use App\Models\ScrapRecord;
use App\Http\Requests\StoreScrapRecordRequest;
use App\Http\Requests\UpdateScrapRecordRequest;
use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Scrap;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ScrapRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $scrapRecords = ScrapRecord::query()
            ->select([
                '*',
                // 'production_plans.id as production_plan_id',
                'part_numbers.id as part_number_id',
                'scraps.id as scrap_id',
                'scrap_records.quantity as quantity_scrap'
            ])
            // ->join('production_plans', 'scrap_records.production_plan_id', '=', 'production_plans.id')
            ->join('part_numbers', 'scrap_records.part_number_id', '=', 'part_numbers.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->join('scraps', 'scrap_records.scrap_id', '=', 'scraps.id')
            ->whereIn('departaments.code', $departamentCode)
            ->orderBy('scrap_records.created_at', 'DESC')
            ->paginate(10);

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
        $arrayClass = ['M1', 'M2', 'M3', 'M4'];
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $partNumbers = PartNumber::select(['part_numbers.number', 'part_numbers.id as part_number_id'])
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->whereIn('item_classes.abbreviation', $arrayClass)
            ->whereIn('departaments.code', $departamentCode)
            ->orderBy('workcenters.number')
            ->orderBy('part_numbers.number')
            ->get();

        $scraps = Scrap::orderBy('code', 'ASC')->get();

        return view('scrap-record.create-scrap', ['partNumbers' => $partNumbers, 'scraps' => $scraps]);
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

    function storeScrap(Request $request)
    {
        $validated = $request->validate([
            'part_number_id' => ['required', 'numeric'],
            'scrap_id' => ['required', 'numeric'],
        ]);

        ScrapRecord::create([
            'part_number_id' => $request->part_number_id,
            'scrap_id' => $request->scrap_id,
            'user_id' => Auth::id(),
            'quantity' => $request->quantity,
        ]);

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

    /**
     *
     */
    public function report()
    {
        return view('scrap-record.report');
    }

    /**
     *
     */
    public function download(Request $request)
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->end)->format('Y-m-d H:i:s');

        $scrapRecords = ScrapRecord::query()
            ->select([
                'type_scraps.name as type_scrap',
                'scraps.code as scrap_code',
                'scraps.name as scrap_name',
                'scrap_records.quantity as scrap_quatity',
                'part_numbers.number as number_part',
                'departaments.name as departament_name',
                'users.name as user_name',
                'scrap_records.created_at as created_at'
            ])
            ->join('part_numbers', 'scrap_records.part_number_id', '=', 'part_numbers.id')
            ->join('scraps', 'scrap_records.scrap_id', '=', 'scraps.id')
            ->join('type_scraps', 'scraps.type_scrap_id', '=', 'type_scraps.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->join('users', 'scrap_records.user_id', '=', 'users.id')
            ->whereBetween('scrap_records.created_at', [$start, $end])
            ->whereIn('departaments.code', $departamentCode)
            ->orderBy('scrap_records.created_at', 'DESC')
            ->get()
            ->toArray();

        return Excel::download(new ScrapRecordExport($scrapRecords), 'ScrapReport_' . date("dmY") . '.xlsx');
    }
}