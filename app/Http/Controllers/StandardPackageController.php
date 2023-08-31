<?php

namespace App\Http\Controllers;

use App\Models\StandardPackage;
use App\Http\Requests\StoreStandardPackageRequest;
use App\Http\Requests\UpdateStandardPackageRequest;

class StandardPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $standardPackages = StandardPackage::query()->orderBy('name', 'DESC')->paginate(10);

        return view('standardPackage.index', ['standardPackages' => $standardPackages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('standardPackage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStandardPackageRequest $request)
    {
        $standardPackage =  StandardPackage::create($request->validated());

        return redirect()->route('sta$standardPackage.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(StandardPackage $standardPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StandardPackage $standardPackage)
    {
        return view('standardPackage.edit', ['standardPackage' => $standardPackage]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStandardPackageRequest $request, StandardPackage $standardPackage)
    {
        $standardPackage->fill($request->validated());

        if ($standardPackage->isDirty()) {
            $standardPackage->save();
        }

        return redirect()->route('standardPackage.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StandardPackage $standardPackage)
    {
        $standardPackage->delete();

        return redirect()->back();
    }
}
