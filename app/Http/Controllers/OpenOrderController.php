<?php

namespace App\Http\Controllers;

use App\Models\Fso;
use App\Models\Yf005;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OpenOrderController extends Controller
{
    public function index(Request $request)
    {
        $swrkc = $request->swrkc ?? '';
        $sord = $request->sord ?? '';
        $sprod = $request->sprod ?? '';
        $date = $request->due_date != '' ? Carbon::parse($request->due_date)->format('Ymd') : '';

        $openOrders = Fso::query()
            ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN', 'SSTAT'])
            ->where('SID', '=', 'SO')
            ->where('SSTAT', '!=', 'X')
            ->where('SSTAT', '!=', 'Y')
            ->Where('SDDTE', '<=', $date)
            ->whereColumn('SQREQ', '>', 'SQFIN')
            ->Where('SORD', 'LIKE', '%' . $sord . '%')
            ->Where('SWRKC', 'LIKE', '%' . $swrkc . '%')
            ->Where('SPROD', 'LIKE', '%' . $sprod . '%')
            ->orderBy('SORD', 'DESC')
            ->orderBy('SDDTE', 'DESC')
            ->simplePaginate(100);

        $totalOpenOrders = Fso::query()
            ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN', 'SSTAT'])
            ->where('SID', '=', 'SO')
            ->where('SSTAT', '!=', 'X')
            ->where('SSTAT', '!=', 'Y')
            ->Where('SDDTE', '<=', $date)
            ->whereColumn('SQREQ', '>', 'SQFIN')
            ->Where('SORD', 'LIKE', '%' . $sord . '%')
            ->Where('SWRKC', 'LIKE', '%' . $swrkc . '%')
            ->Where('SPROD', 'LIKE', '%' . $sprod . '%')
            ->count();

        return view('openOrders.index', ['openOrders' => $openOrders, 'totalOrder' => $totalOpenOrders]);
    }

    public function create(Request $request)
    {
        //
    }

    public function store(Request $request)
    {

        foreach ($request->arrayOpenOrders as $arrayOpenOrder) {

            $cdte = !$arrayOpenOrder['cdte'] == null ? Carbon::parse($arrayOpenOrder['cdte'])->format('Ymd') : '';
            $canc = $arrayOpenOrder['canc'] ?? 0;

            if ($cdte != '') {
                if ($canc != 0) {
                    $data = Yf005::storeOpenOrder($arrayOpenOrder['swrkc'], $arrayOpenOrder['sddte'], $arrayOpenOrder['sord'], $arrayOpenOrder['sprod'], $arrayOpenOrder['sqreq'], $arrayOpenOrder['sqfin'], $cdte, $canc);
                } else {
                    $data = Yf005::storeOpenOrder($arrayOpenOrder['swrkc'], $arrayOpenOrder['sddte'], $arrayOpenOrder['sord'], $arrayOpenOrder['sprod'], $arrayOpenOrder['sqreq'], $arrayOpenOrder['sqfin'], $cdte, $canc);
                }
            } elseif ($canc != 0) {
                $data = Yf005::storeOpenOrder($arrayOpenOrder['swrkc'], $arrayOpenOrder['sddte'], $arrayOpenOrder['sord'], $arrayOpenOrder['sprod'], $arrayOpenOrder['sqreq'], $arrayOpenOrder['sqfin'], $cdte, $canc);
            }
        }

        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YSF004C";
        $result = odbc_exec($conn, $query);

        return redirect()->back();
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
