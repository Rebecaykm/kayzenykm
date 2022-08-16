<?php

namespace App\Http\Controllers;

use App\Models\Fso;
use App\Models\Lwk;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $work = $request->workCenter ?? '';
        $date = $request->dueDate != '' ? Carbon::parse($request->dueDate)->format('Ymd') : '';

        $workCenters = Lwk::query()
            ->select('WWRKC', 'WDESC')
            ->orderBy('WWRKC', 'ASC')
            ->get();

        $dailyProduction = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID'])
            ->where('SWRKC', '=', $work)
            ->where('SDDTE', '=', $date)
            // ->where('SOCNO', '=', '%D%')
            ->orderBy('SOCNO', 'ASC')
            ->orderBy('SDDTE', 'DESC')
            ->simplePaginate(100);

        return view('dailyProduction.index', [
            'dailyProdcution' => $dailyProduction,
            'workCenters' => $workCenters,
            'work'  => $work,
            'date' => $date
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
