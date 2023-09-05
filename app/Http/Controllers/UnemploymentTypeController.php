<?php

namespace App\Http\Controllers;

use App\Models\UnemploymentType;
use App\Http\Requests\StoreUnemploymentTypeRequest;
use App\Http\Requests\UpdateUnemploymentTypeRequest;

class UnemploymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unemploymentTypes = UnemploymentType::query()->orderBy('name', 'ASC')->paginate(10);

        return view('unemployment-type.index', ['unemploymentTypes' => $unemploymentTypes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('unemployment-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnemploymentTypeRequest $request)
    {
        $unemploymentType =  UnemploymentType::create($request->validated());

        return redirect()->route('unemployment-type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(UnemploymentType $unemploymentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnemploymentType $unemploymentType)
    {
        return view('unemployment-type.edit', ['unemploymentType' => $unemploymentType]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnemploymentTypeRequest $request, UnemploymentType $unemploymentType)
    {
        $unemploymentType->fill($request->validated());

        if ($unemploymentType->isDirty()) {
            $unemploymentType->save();
        }

        return redirect()->route('unemployment-type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnemploymentType $unemploymentType)
    {
        $unemploymentType->delete();

        return redirect()->back();
    }
}
