<?php

namespace App\Http\Controllers;

use App\Models\Workcenter;
use App\Http\Requests\StoreWorkcenterRequest;
use App\Http\Requests\UpdateWorkcenterRequest;
use App\Jobs\WorkcenterMigrationJob;
use App\Models\Departament;
use App\Models\LWK;

class WorkcenterController extends Controller
{
    /**
     *
     */
    public function dataUpload()
    {
        WorkcenterMigrationJob::dispatch();

        return redirect('workcenter');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workcenters = Workcenter::query()->orderBy('number', 'DESC')->paginate(10);

        return view('workcenter.index', ['workcenters' => $workcenters]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departaments = Departament::query()->orderBy('code', 'DESC')->get();

        return view('workcenter.create', ['departaments' => $departaments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkcenterRequest $request)
    {
        $workcenter =  Workcenter::create($request->validated());

        return redirect()->route('workcenter.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workcenter $workcenter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workcenter $workcenter)
    {
        $departaments = Departament::query()->orderBy('code', 'DESC')->get();

        return view('workcenter.edit', ['departaments' => $departaments, 'workcenter' => $workcenter]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkcenterRequest $request, Workcenter $workcenter)
    {
        $workcenter->fill($request->validated());

        if ($workcenter->isDirty()) {
            $workcenter->save();
        }

        return redirect()->route('workcenter.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workcenter $workcenter)
    {
        $workcenter->delete();

        return redirect()->back();
    }
}
