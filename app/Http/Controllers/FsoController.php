<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpenOrders\EditOpenOrderRequest;
use App\Models\Fso;
use App\Models\FsoLocal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FsoController extends Controller
{

    public function index()
    {
        $openOrders = Fso::query()
            ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])
            ->where('SID', '=', 'SO')
            ->orderBy('SDDTE', 'DESC')
            ->simplePaginate(5);

        return view('openOrders.index', ['openOrders' => $openOrders]);
    }

    public function create(Request $request)
    {
        dd($request->all());
        $openOrder = Fso::query()->select(['SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])->where('SORD', '=', $request->sord)->first();

        return view('openOrders.edit', ['openOrder' => $openOrder]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->cdte == null){
            $cdte = Carbon::parse($request->cdte)->format('Ymd');
        } else {
            $cdte = '';
        }
        if (!$request->canc == null){
            $canc = $request->canc;
        } else {
            $canc = '0';
        }

        $fso = FsoLocal::create([
            'SID' => $request->sid,
            'SWRKC' => $request->swrkc,
            'SDDTE' => $request->sddte,
            'SORD' => $request->sord,
            'SPROD' => $request->sprod,
            'SQREQ' => $request->sqreq,
            'SQFIN' => $request->sqfin,
            'CDTE' => $cdte,
            'CANC' => $canc,
        ]);

        return redirect('open-orders');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request)
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
