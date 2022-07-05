<?php

namespace App\Http\Controllers;

use App\Models\Demo;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fso = Demo::query()->select(['SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])->orderBy('SDDTE', 'DESC')->paginate(5);
        dd($fso);

//        $data = DB::connection('odbc-connection-infor')->table('LX834F02.FSO')->select('SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN')->orderBy('SDDTE', 'DESC')->limit(20)->get();
//        dd($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
