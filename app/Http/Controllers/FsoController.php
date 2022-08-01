<?php

namespace App\Http\Controllers;

use App\Models\Fso;
use App\Models\Yf005;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FsoController extends Controller
{

    private $due_date;

    public function index(Request $request)
    {
        $this->due_date = Carbon::parse($request->due_date)->format('Ymd') ?? Carbon::now()->format('Ymd');

        $openOrders = Fso::query()
            ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN', 'SSTAT'])
            ->where('SID', '=', 'SO')
            ->where('SSTAT', '!=', 'X')
            ->where('SSTAT', '!=', 'Y')
            ->Where('SDDTE', '<=', $this->due_date)
            ->orderBy('SDDTE', 'DESC')
            ->simplePaginate(100);
        return view('openOrders.index', ['openOrders' => $openOrders, 'dueDate' => $this->due_date]);
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
                    $data = Yf005::query()->insert([
                        'F5ID' => 'SO',
                        'F5WRKC' => $arrayOpenOrder['swrkc'],
                        'F5DDTE' => $arrayOpenOrder['sddte'],
                        'F5ORD' => $arrayOpenOrder['sord'],
                        'F5PROD' => $arrayOpenOrder['sprod'],
                        'F5QREQ' => $arrayOpenOrder['sqreq'],
                        'F5QFIN' => $arrayOpenOrder['sqfin'],
                        'F5CDTE' => $cdte,
                        'F5CAN' => $canc,
                    ]);
                } else {
                    $data = Yf005::query()->insert([
                        'F5ID' => 'SO',
                        'F5WRKC' => $arrayOpenOrder['swrkc'],
                        'F5DDTE' => $arrayOpenOrder['sddte'],
                        'F5ORD' => $arrayOpenOrder['sord'],
                        'F5PROD' => $arrayOpenOrder['sprod'],
                        'F5QREQ' => $arrayOpenOrder['sqreq'],
                        'F5QFIN' => $arrayOpenOrder['sqfin'],
                        'F5CDTE' => $cdte,
                        'F5CAN' => $canc,
                    ]);
                }
            } elseif ($canc != 0) {
                $data = Yf005::query()->insert([
                    'F5ID' => 'SO',
                    'F5WRKC' => $arrayOpenOrder['swrkc'],
                    'F5DDTE' => $arrayOpenOrder['sddte'],
                    'F5ORD' => $arrayOpenOrder['sord'],
                    'F5PROD' => $arrayOpenOrder['sprod'],
                    'F5QREQ' => $arrayOpenOrder['sqreq'],
                    'F5QFIN' => $arrayOpenOrder['sqfin'],
                    'F5CDTE' => $cdte,
                    'F5CAN' => $canc,
                ]);
            }
        }
        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YSF004C";
        $result = odbc_exec($conn, $query);

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
