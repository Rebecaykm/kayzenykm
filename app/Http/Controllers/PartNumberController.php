<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Http\Requests\StorePartNumberRequest;
use App\Http\Requests\UpdatePartNumberRequest;

class PartNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partNumbers = PartNumber::query()->orderBy('number', 'ASC')->paginate();

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
