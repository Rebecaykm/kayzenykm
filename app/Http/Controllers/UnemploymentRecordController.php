<?php

namespace App\Http\Controllers;

use App\Models\UnemploymentRecord;
use App\Http\Requests\StoreUnemploymentRecordRequest;
use App\Http\Requests\UpdateUnemploymentRecordRequest;
use App\Models\Unemployment;
use App\Models\Workcenter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UnemploymentRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unemploymentRecords = UnemploymentRecord::query()->orderBy('created_at', 'DESC')->paginate(10);

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
}
