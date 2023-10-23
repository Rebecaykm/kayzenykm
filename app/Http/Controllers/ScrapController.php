<?php

namespace App\Http\Controllers;

use App\Models\Scrap;
use App\Http\Requests\StoreScrapRequest;
use App\Http\Requests\UpdateScrapRequest;
use App\Models\TypeScrap;

class ScrapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scraps = Scrap::query()->orderBy('code', 'ASC')->paginate(10);

        return view('scrap.index', ['scraps' => $scraps]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $typeScraps = TypeScrap::query()->orderBy('name', 'ASC')->get();

        return view('scrap.create', ['typeScraps' => $typeScraps]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScrapRequest $request)
    {
        $scrap =  Scrap::create($request->validated());

        return redirect('scrap');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scrap $scrap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scrap $scrap)
    {
        $typeScraps = TypeScrap::query()->orderBy('name', 'ASC')->get();

        return view('scrap.edit', ['scrap' => $scrap, 'typeScraps' => $typeScraps]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScrapRequest $request, Scrap $scrap)
    {
        $scrap->fill($request->validated());

        if ($scrap->isDirty()) {
            $scrap->save();
        }

        return redirect('scrap');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scrap $scrap)
    {
        $scrap->delete();

        return redirect()->back();
    }
}
