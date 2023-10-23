<?php

namespace App\Http\Controllers;

use App\Models\Planner;
use App\Http\Requests\StorePlannerRequest;
use App\Http\Requests\UpdatePlannerRequest;
use App\Jobs\PlannerMigrationJob;

class PlannerController extends Controller
{
    /**
     *
     */
    function dataUpload()
    {
        PlannerMigrationJob::dispatch();

        return redirect('planner');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planners = Planner::query()->orderBy('name', 'ASC')->paginate(10);

        return view('planner.index', ['planners' => $planners]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('planner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlannerRequest $request)
    {
        $planner =  Planner::create($request->validated());

        return redirect()->route('planner.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Planner $planner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Planner $planner)
    {
        return view('planner.edit', ['planner' => $planner]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlannerRequest $request, Planner $planner)
    {
        $planner->fill($request->validated());

        if ($planner->isDirty()) {
            $planner->save();
        }

        return redirect()->route('planner.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Planner $planner)
    {
        $planner->delete();

        return redirect()->back();
    }
}
