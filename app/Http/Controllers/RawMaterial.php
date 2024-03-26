<?php

namespace App\Http\Controllers;

use App\Jobs\CheckPackNumberJob;
use Illuminate\Http\Request;

class RawMaterial extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('raw-material');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pack' => ['required', 'min:9', 'max:15']
        ], [
            'pack.required' => 'Se requiere ingresar el número de pack',
            'pack.min' => 'El número de pack debe tener al menos 9 dígitos',
            'pack.max' => 'El número de pack no puede tener más de 15 dígitos'
        ]);

        $packNumber = trim(strval($request->pack));

        CheckPackNumberJob::dispatch(
            $packNumber
        );

        return redirect()->back()->with('success', 'Escaneado');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
