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
use App\Models\YF020;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                'workcenters.id as workcenter_id',
                'part_numbers.id as part_number_id',
                'scraps.id as scrap_id',
                'scrap_records.quantity as quantity_scrap',
                'scrap_records.id as id'
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
        try {
            DB::transaction(function () use ($request) {
                $productionPlan = ProductionPlan::findOrFail($request->production_plan_id);
                $scrap = Scrap::findOrFail($request->scrap_id);

                $total = 0;

                ScrapRecord::create([
                    'production_plan_id' => $request->production_plan_id,
                    'part_number_id' => $request->part_number_id,
                    'scrap_id' => $scrap->id,
                    'user_id' => Auth::id(),
                    'quantity' => $request->quantity,
                ]);

                $total = $productionPlan->scrap_quantity + $request->quantity;
                $productionPlan->update(['scrap_quantity' => $total]);
            });

            return redirect()->back()->with('success', '¡Registro de scrap exitoso!');
        } catch (\Exception $e) {
            Log::error('ScrapRecordController: ' . $e->getMessage());

            return redirect()->back()->with('error', '¡Error! Fallo en el registro de scrap.');
        }
    }

    function storeScrap(Request $request)
    {
        $validated = $request->validate(
            [
                'part_number_id' => ['required', 'numeric'],
                'scrap_id' => ['required', 'numeric'],
                'quantity' => ['required', 'integer', 'min:1', 'max:99']
            ],
            [
                'part_number_id.required' => 'Debes seleccionar un número de parte.',
                'part_number_id.numeric' => 'Debes seleccionar un número de parte.',
                'scrap_id.numeric' => 'Debes seleccionar un Tipo de Scrap.',
                'scrap_id.required' => 'Debes seleccionar un Tipo de Scrap.',
                'quantity.required' => 'Debes ingresar una cantidad válida.',
                'quantity.min' => 'La cantidad no puede ser negativa o cero.',
                'quantity.max' => 'La cantidad no puede ser mayor a 99.',

            ]
        );

        try {
            DB::transaction(function () use ($request) {
                $partNumber = PartNumber::findOrFail($request->part_number_id);
                $scrap = Scrap::findOrFail($request->scrap_id);

                $scrapRecord = ScrapRecord::create([
                    'part_number_id' => $partNumber->id,
                    'scrap_id' => $scrap->id,
                    'user_id' => Auth::id(),
                    'quantity' => $request->quantity,
                    'flag' => 1
                ]);

                $yf020 =  YF020::query()->insert([
                    'YSWRKC' => $partNumber->workcenter->number,
                    'YSWRKN' => $partNumber->workcenter->name,
                    // 'YSRDTE' => ,
                    // 'YSSHFT' => ,
                    // 'YSPPNO' => ,
                    'YSPROD' => $partNumber->number,
                    'YSQSCR' => $request->quantity,
                    'YSSCRE' => $scrap->code,
                    'YSCRDT' => Carbon::now()->format('Ymd'),
                    'YSCRTM' => Carbon::now()->format('His'),
                    'YSCRUS' => Auth::user()->infor ?? '',
                    // 'YSCRWS' => ,
                    // 'YSFIL1' => ,
                    // 'YSFIL2' => ,
                ]);
            });

            return redirect()->back()->with('success', '¡Registro de scrap exitoso!');
        } catch (\Exception $e) {
            Log::error('Error en ScrapRecordController :' . $e->getMessage());

            return redirect()->back()->with('error', '¡Error! Fallo en el registro de scrap.');
        }
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
        $scraps = Scrap::orderBy('code', 'ASC')->get();

        return view('scrap-record.edit', ['scrapRecord' => $scrapRecord, 'scraps' => $scraps]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScrapRecordRequest $request, ScrapRecord $scrapRecord)
    {
        $scrapRecord->fill($request->validated());

        if ($scrapRecord->isDirty()) {
            $scrapRecord->save();
        }

        return redirect()->back()->with('success', '¡Actualización exitosa!');
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
        $validated = $request->validate(
            [
                'start' => ['required'],
                'end' => ['required', 'after:start'],
            ],
            [
                'start.required' => 'La fecha inicio es necesaria',
                'end.required' => 'La fecha final es necesaria',
                'end.after' => 'La fecha final no puede se una fecha igual o posterior a la fecha inicio',

            ]
        );

        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $start = Carbon::parse($request->start)->format('Y-d-m H:i:s');
        $end = Carbon::parse($request->end)->format('Y-d-m H:i:s');

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

        return Excel::download(new ScrapRecordExport($scrapRecords), 'ScrapReport_' . date("dmYHis") . '.xlsx');
    }
}
