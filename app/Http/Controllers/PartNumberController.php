<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Http\Requests\StorePartNumberRequest;
use App\Http\Requests\UpdatePartNumberRequest;
use App\Jobs\PartNumberMigrationJob;
use Illuminate\Http\Request;

class PartNumberController extends Controller
{
    /**
     *
     */
    function dataUpload()
    {
        PartNumberMigrationJob::dispatch();

        return redirect('part-number');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $partNumbers = PartNumber::query()->where('number', 'LIKE', '%' . $search . '%')->orderBy('updated_at', 'DESC')->paginate(10);

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
