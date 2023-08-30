<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Http\Requests\StoreMeasurementRequest;
use App\Http\Requests\UpdateMeasurementRequest;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $measurements = Measurement::query()->orderBy('symbol', 'DESC')->paginate(10);

        return view('measurement.index', ['measurements' => $measurements]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('measurement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeasurementRequest $request)
    {
        $measurement =  Measurement::create($request->validated());

        return redirect()->route('measurement.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Measurement $measurement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Measurement $measurement)
    {
        return view('measurement.edit', ['measurement' => $measurement]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMeasurementRequest $request, Measurement $measurement)
    {
        $measurement->fill($request->validated());

        if ($measurement->isDirty()) {
            $measurement->save();
        }

        return redirect()->route('measurement.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Measurement $measurement)
    {
        $measurement->delete();

        return redirect()->back();
    }
}
