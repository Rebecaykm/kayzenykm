<?php

namespace App\Http\Controllers;

use App\Models\ItemClass;
use App\Http\Requests\StoreItemClassRequest;
use App\Http\Requests\UpdateItemClassRequest;

class ItemClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemClasses = ItemClass::query()->orderBy('name', 'DESC')->paginate(10);

        return view('itemClass.index', ['itemClasses' => $itemClasses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('itemClass.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemClassRequest $request)
    {
        $itemClass =  ItemClass::create($request->validated());

        return redirect()->route('itemClass.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemClass $itemClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemClass $itemClass)
    {
        return view('itemClass.edit', ['itemClass' => $itemClass]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemClassRequest $request, ItemClass $itemClass)
    {
        $itemClass->fill($request->validated());

        if ($itemClass->isDirty()) {
            $itemClass->save();
        }

        return redirect()->route('itemClass.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemClass $itemClass)
    {
        $itemClass->delete();

        return redirect()->back();
    }
}
