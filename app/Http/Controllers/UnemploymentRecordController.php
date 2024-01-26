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
use Maatwebsite\Excel\Facades\Excel;

class UnemploymentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

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
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->join('unemployments', 'unemployment_records.unemployment_id', '=', 'unemployments.id')
            ->join('unemployment_types', 'unemployments.unemployment_type_id', '=', 'unemployment_types.id')
            ->whereIn('departaments.code', $departamentCode)
            ->orderBy('unemployment_records.created_at', 'DESC')
            ->paginate(10);

        return view('unemployment-record.index', ['unemploymentRecords' => $unemploymentRecords]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user()->departaments->pluck('id')->toArray();

        $workcenters = Workcenter::whereHas('departament', function ($query) use ($user) {
            $query->whereIn('departaments.id', $user);
        })
            ->orderBy('number', 'asc')
            ->get();

        $unemployments = Unemployment::orderBy('name', 'asc')->get();

        return view('unemployment-record.create', ['workcenters' => $workcenters, 'unemployments' => $unemployments]);
    }

    function record()
    {
        $user = Auth::user()->departaments->pluck('id')->toArray();

        $workcenters = Workcenter::whereHas('departament', function ($query) use ($user) {
            $query->whereIn('departaments.id', $user);
        })
            ->orderBy('number', 'asc')
            ->get();

        $unemployments = Unemployment::orderBy('name', 'asc')->get();

        return view('unemployment-record.register', ['workcenters' => $workcenters, 'unemployments' => $unemployments]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnemploymentRecordRequest $request)
    {
        $start = new Carbon($request->time_start);
        $end = new Carbon($request->time_end);
        $minutes = $end->diffInMinutes($start);

        UnemploymentRecord::create([
            'user_id' => Auth::id(),
            'workcenter_id' => $request->workcenter_id,
            'unemployment_id' => $request->unemployment_id,
            'time_start' => $start,
            'time_end' => $end,
            'minutes' => $minutes,
        ]);

        return redirect()->back();
    }

    function save(StoreUnemploymentRecordRequest $request)
    {
        $start = new Carbon($request->time_start);
        $end = new Carbon($request->time_end);
        $minutes = $end->diffInMinutes($start);

        UnemploymentRecord::create([
            'user_id' => Auth::id(),
            'workcenter_id' => $request->workcenter_id,
            'unemployment_id' => $request->unemployment_id,
            'time_start' => $start,
            'time_end' => $end,
            'minutes' => $minutes,
        ]);

        return redirect()->back();
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
                'start.required' => 'La fecha inicio es necesaria',
                'end.required' => 'La fecha final es necesaria',
                'end.after' => 'La fecha final no puede se una fecha igual o posterior a la fecha inicio',

            ]
        );

        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

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
                'unemployment_records.created_at'
            ])
            ->join('unemployments', 'unemployment_records.unemployment_id', '=', 'unemployments.id')
            ->join('unemployment_types', 'unemployments.unemployment_type_id', '=', 'unemployment_types.id')
            ->join('workcenters', 'unemployment_records.workcenter_id', '=', 'workcenters.id')
            ->join('departaments', 'workcenters.departament_id', '=', 'departaments.id')
            ->whereBetween('unemployment_records.created_at', [$start, $end])
            ->whereIn('departaments.code', $departamentCode)
            ->orderBy('unemployment_records.created_at', 'DESC')
            ->get()
            ->toArray();

        return Excel::download(new UnemploymentRecordExport($unemploymentRecords), 'UnemploymentReport_' . date("dmYHis") . '.xlsx');
    }
}
