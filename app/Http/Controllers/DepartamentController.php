<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartamentRequest;
use App\Http\Requests\UpdateDepartamentRequest;
use App\Models\Departament;

class DepartamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departaments = Departament::query()->orderBy('code', 'ASC')->paginate(10);

        return view('departament.index', ['departaments' => $departaments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departament.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartamentRequest $request)
    {
        $departament =  Departament::create($request->validated());

        return redirect()->route('departament.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departament $departament)
    {
        return view('departament.edit', ['departament' => $departament]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartamentRequest $request, Departament $departament)
    {
        $departament->fill($request->validated());

        if ($departament->isDirty()) {
            $departament->save();
        }

        return redirect()->route('departament.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departament $departament)
    {
        $departament->delete();

        return redirect()->back();
    }
}
