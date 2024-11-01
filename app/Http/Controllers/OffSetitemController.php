<?php

namespace App\Http\Controllers;

use App\Models\YMLTM;
use Illuminate\Http\Request;

class OffSetitemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $ofs=YMLTM::query()
    ->join('LX834F01.IIM', 'LTPROD', '=', 'IPROD')
    ->select('LTID','LTPROD','LTLDTM','LTCUSR','LTCCDT','LTCCTM','LTFIL1','LTFIL2')
                ->where([
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],
                ])
                ->get()->toarray();

                return view('planeacion.index', ['partes' => $ofs]);
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
    public function store(Request $request)
    {
        //
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
