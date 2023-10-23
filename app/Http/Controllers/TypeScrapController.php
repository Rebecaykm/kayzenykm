<?php

namespace App\Http\Controllers;

use App\Models\TypeScrap;
use App\Http\Requests\StoreTypeScrapRequest;
use App\Http\Requests\UpdateTypeScrapRequest;

class TypeScrapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeScraps = TypeScrap::query()->orderBy('name', 'ASC')->paginate(10);

        return view('type-scrap.index', ['typeScraps' => $typeScraps]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('type-scrap.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeScrapRequest $request)
    {
        $scrap =  TypeScrap::create($request->validated());

        return redirect('type-scrap');
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeScrap $typeScrap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeScrap $typeScrap)
    {
        return view('type-scrap.edit', ['typeScrap' => $typeScrap]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeScrapRequest $request, TypeScrap $typeScrap)
    {
        $typeScrap->fill($request->validated());

        if ($typeScrap->isDirty()) {
            $typeScrap->save();
        }

        return redirect('type-scrap');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeScrap $typeScrap)
    {
        $typeScrap->delete();

        return redirect()->back();
    }
}
