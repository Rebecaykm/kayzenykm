<?php

namespace App\Http\Controllers;

use App\Models\Fso;
use App\Models\Lwk;
use App\Models\Yf006;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyProductionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $work = $request->workCenter ?? '';
        $date = $request->dueDate != '' ? Carbon::parse($request->dueDate)->format('Ymd') : '';

        $workCenters = Lwk::query()
            ->select('WWRKC', 'WDESC')
            ->orderBy('WWRKC', 'ASC')
            ->get();

        $dailyDiurno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID'])
            ->where('SWRKC', '=', $work)
            ->where('SDDTE', '=', $date)
            ->where('SOCNO', 'NOT LIKE', '%N%')
            ->orderBy('SOCNO', 'ASC')
            ->simplePaginate(100);

        $dailyNocturno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID'])
            ->where('SWRKC', '=', $work)
            ->where('SDDTE', '=', $date)
            ->where('SOCNO', 'LIKE', '%N%')
            ->orderBy('SOCNO', 'ASC')
            ->simplePaginate(100);

        return view('dailyProduction.index', [
            'dailyDiurnos' => $dailyDiurno,
            'dailyNocturnos' => $dailyNocturno,
            'workCenters' => $workCenters,
            'work'  => $work,
            'date' => $date
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexUser(Request $request)
    {
        $work = $request->workCenter ?? '';
        $date = $request->dueDate != '' ? Carbon::parse($request->dueDate)->format('Ymd') : '';

        $workCenters = Lwk::query()
            ->select('WWRKC', 'WDESC')
            ->orderBy('WWRKC', 'ASC')
            ->get();

        $dailyDiurno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID'])
            ->where('SWRKC', '=', $work)
            ->where('SDDTE', '=', $date)
            ->where('SOCNO', 'NOT LIKE', '%N%')
            ->orderBy('SOCNO', 'ASC')
            ->simplePaginate(100);

        $dailyNocturno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID'])
            ->where('SWRKC', '=', $work)
            ->where('SDDTE', '=', $date)
            ->where('SOCNO', 'LIKE', '%N%')
            ->orderBy('SOCNO', 'ASC')
            ->simplePaginate(100);

        return view('dailyProduction.user', [
            'dailyDiurnos' => $dailyDiurno,
            'dailyNocturnos' => $dailyNocturno,
            'workCenters' => $workCenters,
            'work'  => $work,
            'date' => $date
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        foreach ($request->arrayDailyProductions as $arrayDaily) {
            $data = Fso::query()
                ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN', 'SQREMM'])
                ->where('SORD', '=', $arrayDaily['sord'])
                ->first();

            if ($data->SID == 'SO') {

                $cdte = !$arrayDaily['cdte'] == null ? Carbon::parse($arrayDaily['cdte'])->format('Ymd') : '';
                $canc = $arrayDaily['canc'] ?? 0;
                $sqfin = $arrayDaily['sqfin'];
                $sqremm = $arrayDaily['sqremm'];

                $insert = Yf006::storeDailyProduction($data->SID, $data->SWRKC, $data->SDDTE, $data->SORD, $data->SPROD, $data->SQREQ, $sqfin, $sqremm, $canc, $cdte);
            }
        }
        dd("Program");
        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YSF008C";
        $result = odbc_exec($conn, $query);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
