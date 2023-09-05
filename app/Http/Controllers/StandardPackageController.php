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

        return view('standard-package.index', ['standardPackages' => $standardPackages]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('standard-package.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStandardPackageRequest $request)
    {
        $standardPackage =  StandardPackage::create($request->validated());

        return redirect()->route('standard-package.index');
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
        return view('standard-package.edit', ['standardPackage' => $standardPackage]);
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

        return redirect()->route('standard-package.index');
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
