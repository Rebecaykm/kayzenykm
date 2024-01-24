<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan;
use App\Http\Requests\StoreProductionPlanRequest;
use App\Http\Requests\UpdateProductionPlanRequest;
use App\Jobs\ProductionPlanMigrationJob;
use App\Models\IPYF013;
use App\Models\PartNumber;
use App\Models\ProdcutionRecord;
use App\Models\Shift;
use App\Models\Status;
use Carbon\Carbon;
use Database\Seeders\ShiftSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $search = strtoupper($request->search) ?? '';

        $startWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $arrayClass = ['M1', 'M2', 'M3', 'M4'];
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $status = Status::where('name', 'INACTIVO')->first();

        $productionPlans = ProductionPlan::select([
            '*',
            'production_plans.id as production_plan_id',
            'part_numbers.id as part_number_id',
            'item_classes.id as item_class_id',
            'workcenters.id as workcenter_id',
            'departaments.id as departament_id',
            'shifts.id as shift_id',
            'statuses.id as status_id'
        ])
            ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
            ->join('statuses', 'production_plans.status_id', '=', 'statuses.id')
            ->where('part_numbers.number', 'LIKE', '%' . $search . '%')
            ->where('production_plans.status_id', '!=', $status->id)
            ->whereIn('item_classes.abbreviation', $arrayClass)
            ->whereIn('departaments.code', $departamentCode)
            // ->whereBetween('production_plans.date', [$startWeek, $endWeek])
            ->orderBy('production_plans.date', 'asc')
            ->orderBy('shifts.abbreviation', 'asc')
            ->orderBy('part_numbers.number')
            ->orderBy('workcenters.number', 'asc')
            ->paginate(10);

        return view('production-plan.index', ['productionPlans' => $productionPlans]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $arrayClass = ['M1', 'M2', 'M3', 'M4'];
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $partNumbers = PartNumber::select(['part_numbers.number', 'part_numbers.id as part_number_id'])
            ->join('item_classes', 'part_numbers.item_class_id', '=', 'item_classes.id')
            ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->whereIn('item_classes.abbreviation', $arrayClass)
            ->whereIn('departaments.code', $departamentCode)
            // ->orderBy('workcenters.number', 'asc')
            ->orderBy('part_numbers.number', 'asc')
            ->get();

        $shifts = Shift::orderBy('abbreviation', 'asc')->get();

        return view(
            'production-plan.create',
            ['parts' => $partNumbers, 'shifts' => $shifts]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionPlanRequest $request)
    {
        $productionPlan = ProductionPlan::create(
            [
                'part_number_id' => $request->partNumber,
                'plan_quantity' => $request->planQuantity,
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'shift_id' => $request->shift,
                'status_id' => 1
            ]
        );

        return redirect()->back();
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
    }

    public function disable(Request $request)
    {
        try {
            DB::transaction(function ()  use ($request) {

                $status = Status::where('name', 'INACTIVO')->first();

                $productionPlan = ProductionPlan::where('id', $request->production)->first();

                $records = ProdcutionRecord::where('production_plan_id', $productionPlan->id)->get();

                $timeStart = Carbon::parse($records->min('time_start'))->format('Hi');
                $timeEnd = Carbon::parse($records->max('time_end'))->format('Hi');

                $dateStart = Carbon::parse($records->min('created_at'))->format('Ymd');
                $dateEnd = Carbon::parse($records->max('created_at'))->format('Ymd');

                $ipyf03 = IPYF013::query()->insert([
                    'YFWRKC' => $productionPlan->partNumber->workcenter->number,
                    'YFWRKN' => $productionPlan->partNumber->workcenter->name,
                    'YFRDTE' => Carbon::parse($productionPlan->date)->format('Ymd'),
                    'YFSHFT' => $productionPlan->shift->abbreviation,
                    'YFPPNO' => $productionPlan->productionRecords()->latest('sequence')->value('sequence'),
                    'YFPROD' => $productionPlan->partNumber->number,
                    'YFSTIM' => $timeStart,
                    'YFETIM' => $timeEnd,
                    'YFSDT' => $dateStart . $timeStart,
                    'YFEDT' => $dateEnd . $timeEnd,
                    'YFQPLA' => $productionPlan->plan_quantity,
                    'YFQPRO' => $productionPlan->production_quantity,
                    // 'YFQSCR' => $productionPlan->scrapRecords->sum('quantity'),
                    // 'YFSCRE' => ,
                    'YFCRDT' => Carbon::now()->format('Ymd'),
                    'YFCRTM' => Carbon::now()->format('His'),
                    'YFCRUS' => Auth::user()->infor ?? '',
                    // 'YFCRWS' => ,
                    // 'YFFIL1' => ,
                    // 'YFFIL2' => ,
                ]);

                // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
                // $query = "CALL LX834OU.YSF013B";
                // $result = odbc_exec($conn, $query);

                // if ($result) {
                //     Log::info("La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
                // } else {
                //     Log::info("Error en la consulta: " . odbc_errormsg($conn));
                // }

                // odbc_close($conn);

                $productionPlan->update(['status_id' => $status->id]);
            });
        } catch (\Exception $e) {
            Log::error('ProductionPlanController: ' . $e->getMessage());
        }
        return redirect()->back();
    }
}
