<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Http\Requests\StorePartNumberRequest;
use App\Http\Requests\UpdatePartNumberRequest;
use App\Jobs\PartNumberMigrationJob;

class PartNumberController extends Controller
{
    /**
     *
     */
    function dataUpload()
    {
        PartNumberMigrationJob::dispatch();

        // return redirect('part-number.index');

        // $partNumbers = IIM::query()->select('IDESC', 'IPROD', 'IOPB', 'IUMS', 'IITYP', 'ICLAS', 'IMSPKT', 'IMBOXQ', 'IBUYC', 'IREF04', 'IMENDT', 'IMENTM')->orderBy('IMENDT', 'ASC')->get();

        // foreach ($partNumbers as $key => $partNumber) {


        //     $itemType = Type::where('abbreviation', '=', $partNumber->IITYP)->first();
        //     $itemClass = ItemClass::where('abbreviation', '=', $partNumber->ICLAS)->first();
        //     $measurementType = Measurement::where('symbol', '=', $partNumber->IUMS)->first();
        //     $plannerCode = Planner::where('code', $partNumber->IBUYC)->first();

        //     PartNumber::updateOrCreate(
        //         [
        //             'number' => $partNumber->IPROD,
        //         ],
        //         [
        //             'name' => preg_replace('([^A-Za-z0-9])', '', $partNumber->IDESC),
        //             'measurement_id' => $measurementType->id,
        //             'type_id' => $itemType->id,
        //             'item_class_id' => $itemClass->id,
        //             'standard_package_id' => 1,
        //             'workcenter_id' => 1,
        //             'planner_id' => $plannerCode->id ?? 1,
        //             'project_id' => 1
        //         ],
        //     );
        // }

        return redirect('part-number');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partNumbers = PartNumber::query()->orderBy('updated_at', 'DESC')->paginate();

        return view('part-number.index', ['partNumbers' => $partNumbers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartNumberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PartNumber $partNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartNumber $partNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartNumberRequest $request, PartNumber $partNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartNumber $partNumber)
    {
        //
    }
}
