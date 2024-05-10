<?php

namespace App\Http\Controllers;

use App\Models\Unemployment;
use App\Http\Requests\StoreUnemploymentRequest;
use App\Http\Requests\UpdateUnemploymentRequest;
use App\Models\UnemploymentType;

class UnemploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unemployments = Unemployment::query()->orderBy('name', 'ASC')->paginate(10);

        return view('unemployment.index', ['unemployments' => $unemployments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unemploymentTypes = UnemploymentType::query()->orderBy('name', 'DESC')->get();

        return view('unemployment.create', ['unemploymentTypes' => $unemploymentTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnemploymentRequest $request)
    {
        $unemployment =  Unemployment::create([
            'abbreviation' => $request->abbreviation,
            'name' => $request->name,
            'unemployment_type_id' => $request->unemployment_type_id ?? null
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Unemployment $unemployment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unemployment $unemployment)
    {
        $unemploymentTypes = UnemploymentType::query()->orderBy('name', 'DESC')->get();

        return view('unemployment.edit', ['unemployment' => $unemployment, 'unemploymentTypes' => $unemploymentTypes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnemploymentRequest $request, Unemployment $unemployment)
    {
        $unemployment->fill($request->validated());

        if ($unemployment->isDirty()) {
            $unemployment->save();
        }

        return redirect()->route('unemployment.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unemployment $unemployment)
    {
        $unemployment->delete();

        return redirect()->back();
    }
}
