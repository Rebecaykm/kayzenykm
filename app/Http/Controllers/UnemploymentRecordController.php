<?php

namespace App\Http\Controllers;

use App\Exports\UnemploymentRecordExport;
use App\Models\UnemploymentRecord;
use App\Http\Requests\StoreUnemploymentRecordRequest;
use App\Http\Requests\UpdateUnemploymentRecordRequest;
use App\Models\Unemployment;
use App\Models\Workcenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UnemploymentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
            return $line->workcenters->pluck('number')->all();
        });

        $unemploymentRecords = UnemploymentRecord::query()
            ->select([
                '*',
                'workcenters.id as workcenter_id',
                'departaments.id as departament_id',
                'unemployments.id as unemployment_id',
                'unemployment_types.id as unemployment_type_id',
                'unemployment_records.created_at as created_at'
            ])
            ->join('workcenters', 'unemployment_records.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->join('unemployments', 'unemployment_records.unemployment_id', '=', 'unemployments.id')
            ->leftJoin('unemployment_types', 'unemployments.unemployment_type_id', '=', 'unemployment_types.id')
            ->whereIn('workcenters.number', $workcenterNumbers)
            ->orderBy('unemployment_records.created_at', 'DESC')
            ->paginate(10);

        return view('unemployment-record.index', ['unemploymentRecords' => $unemploymentRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $workcenters = Workcenter::query()
            ->select('workcenters.id', 'workcenters.number', 'workcenters.name')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->whereIn('workcenters.number', Auth::user()->lines->flatMap->workcenters->pluck('number')->all())
            ->orderBy('workcenters.name', 'asc')
            ->get();

        $userRoles = Auth::user()->roles->pluck('name')->toArray();
        $userRole = in_array('Operador', $userRoles) ? 'Operador' : null;

        $departmentAbbreviations = [
            'Carrocería' => 'CR',
            'Chasis' => 'CH',
            'Pintura' => 'PT',
            'Estampado' => 'PR'
        ];

        $abbreviations = Auth::user()->departaments->pluck('name')
            ->filter(fn ($department) => isset($departmentAbbreviations[$department]))
            ->map(fn ($department) => $departmentAbbreviations[$department])
            ->toArray();

        if ($userRole !== null && in_array('PR', $abbreviations)) {
            $unemployments = Unemployment::whereIn('abbreviation', $abbreviations)->orderBy('name', 'asc')->get();
        } else {
            $abbreviations[] = 'CA';
            $unemployments = Unemployment::whereIn('abbreviation', $abbreviations)->orderBy('name', 'asc')->get();
        }

        return view('unemployment-record.create', compact('workcenters', 'unemployments'));
    }

    function record()
    {
        $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
            return $line->workcenters->pluck('number')->all();
        });

        $workcenters = Workcenter::query()
            ->select('workcenters.id', 'workcenters.number', 'workcenters.name')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->whereIn('workcenters.number', $workcenterNumbers)
            ->orderBy('workcenters.name', 'asc')
            ->get();

        $unemployments = Unemployment::orderBy('name', 'asc')->get();

        return view('unemployment-record.register', ['workcenters' => $workcenters, 'unemployments' => $unemployments]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnemploymentRecordRequest $request)
    {
        try {
            $start = Carbon::parse($request->time_start);
            $end = Carbon::parse($request->time_end);
            $minutes = $end->diffInMinutes($start);

            $unemploymentRecord = UnemploymentRecord::create([
                'user_id' => Auth::id(),
                'workcenter_id' => $request->workcenter_id,
                'unemployment_id' => $request->unemployment_id,
                'time_start' => $start->format('Ymd H:i:s.v'),
                'time_end' => $end->format('Ymd H:i:s.v'),
                'minutes' => $minutes,
                'description' => $request->description ?? ''
            ]);

            return redirect()->back()->with('success', 'Registro de paro exitoso en la estacion: ' . $unemploymentRecord->workcenter->number . " - " .  $unemploymentRecord->workcenter->name);
        } catch (\Exception $e) {
            Log::error('UnemploymentRecordController.- Error en el registro: ' . $e->getMessage());

            return redirect()->back()->with('error', '¡Error! Hubo un problema durante el registro del paro. Por favor, revisa los detalles en los registros.');
        }
    }

    function save(StoreUnemploymentRecordRequest $request)
    {
        try {
            $start = Carbon::parse($request->time_start);
            $end = Carbon::parse($request->time_end);
            $minutes = $end->diffInMinutes($start);

            $unemploymentRecord = UnemploymentRecord::create([
                'user_id' => Auth::id(),
                'workcenter_id' => $request->workcenter_id,
                'unemployment_id' => $request->unemployment_id,
                'time_start' => $start->format('Ymd H:i:s.v'),
                'time_end' => $end->format('Ymd H:i:s.v'),
                'minutes' => $minutes,
                'description' => $request->description ?? ''
            ]);

            return redirect()->back()->with('success', 'Registro de paro exitoso en la estacion: ' . $unemploymentRecord->workcenter->number . " - " .  $unemploymentRecord->workcenter->name);
        } catch (\Exception $e) {
            Log::error('UnemploymentRecordController.- Error en el registro: ' . $e->getMessage());

            return redirect()->back()->with('error', '¡Error! Hubo un problema durante el registro del paro. Por favor, revisa los detalles en los registros.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UnemploymentRecord $unemploymentRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnemploymentRecord $unemploymentRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnemploymentRecordRequest $request, UnemploymentRecord $unemploymentRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnemploymentRecord $unemploymentRecord)
    {
        //
    }

    public function report()
    {
        return view('unemployment-record.report');
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
                'start.required' => 'La fecha de inicio es obligatoria.',
                'end.required' => 'La fecha de finalización es obligatoria.',
                'end.after' => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
            ]
        );

        $workcenterNumbers = Auth::user()->lines->flatMap(function ($line) {
            return $line->workcenters->pluck('number')->all();
        });

        $start = Carbon::parse($request->start)->format('Y-d-m H:i:s');
        $end = Carbon::parse($request->end)->format('Y-d-m H:i:s');

        $unemploymentRecords = UnemploymentRecord::query()
            ->select([
                'unemployment_types.name AS unemployment_type',
                'unemployments.name AS unemployment_name',
                'departaments.name AS departament_name',
                'workcenters.number AS workcenter_number',
                'workcenters.name AS workcenter_name',
                'unemployment_records.time_start',
                'unemployment_records.time_end',
                'unemployment_records.minutes',
                'unemployment_records.created_at',
                'unemployment_records.description',
            ])
            ->join('unemployments', 'unemployment_records.unemployment_id', '=', 'unemployments.id')
            ->leftJoin('unemployment_types', 'unemployments.unemployment_type_id', '=', 'unemployment_types.id')
            ->join('workcenters', 'unemployment_records.workcenter_id', '=', 'workcenters.id')
            ->join('lines', 'workcenters.line_id', '=', 'lines.id')
            ->join('departaments', 'lines.departament_id', '=', 'departaments.id')
            ->whereBetween('unemployment_records.created_at', [$start, $end])
            ->whereIn('workcenters.number', $workcenterNumbers)
            ->orderBy('unemployment_records.created_at', 'DESC')
            ->get()
            ->toArray();

        if (empty($unemploymentRecords)) {
            return back()->with('error', 'No se encontraron registros de scrap para las fechas seleccionadas.');
        }

        return Excel::download(new UnemploymentRecordExport($unemploymentRecords), 'UnemploymentReport_' . date("dmYHis") . '.xlsx');
    }
}
