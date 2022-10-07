<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LWK;
use App\Models\IPB;
use App\Models\KMR;
use App\Models\kFP;
use App\Models\ZCC;
use App\Models\YK006;
use App\Models\Structure;
use Carbon\Carbon;


class PlaneacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return\Illuminate\Http\Response
     */

    public $plan = null;
    public function index(Request $request)
    {

        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $plan = '';
        $TP = 'NO';
        $CP = '';
        $WC = '';
        $this->WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();

        $this->PCs = IPB::query()
            ->select('PBID', 'PBPBC', 'PBTYP', 'PBNAM')
            ->where('PBID', '=', 'PB')
            ->where('PBTYP', '=', 'P')
            ->orderBy('PBNAM', 'ASC')
            ->get();

        return view('planeacion.index', ['LWK' => $this->WCs, 'ipb' => $this->PCs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $dias = $request->dias ?? '7';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $WC = $request->SeWC;

        if ($TP != '') {
            $plan = IPB::query()
                ->select('IPROD', 'ICLAS', 'IMBOXQ')
                ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
                ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
                ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
                ->where([
                    ['IREF04', 'like', '%' . $TP . '%'],
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],

                ])
                ->where(function ($query) {
                    $query->where('ICLAS ', 'F1');
                })
                ->distinct('IPROD')
                ->simplePaginate(5);
        } else {
            $plan = IPB::query()
                ->select('IPROD', 'ICLAS')
                ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
                ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
                ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
                ->where([
                    ['IBUYC', '!=', $CP],
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],
                ])
                ->where(function ($query) {
                    $query->where('ICLAS ', 'F1');
                })
                ->distinct('IPROD')
                ->simplePaginate(5);
        }

        return view('planeacion.plancomponente', ['plan' => $plan, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
    }


    public function store(Request $request)
    {
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request)
    {

        $variables = $request->all();

        $plan = array_keys($variables);

        dd($variables);
        $fecha = $request->fecha;
        $dias = $request->dias;

        foreach ($plan as $plans) {
            $inp = explode('/', $plans, 3);

            if (count($inp) >= 3) {

                $namenA = strtr($inp[0], '_', ' ');
                 dd($variables, $plan, $namenA, $inp[1], $inp[2]);
                $hoy = date('Ymd', strtotime('now'));
                $load = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $cont = 0;
                $fefin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));

                        $data = YK006::query()->insert([
                            'K6PROD' => $namenA,
                            'K6WRKC' => $plans->WWRKC,
                            'K6SDTE' => $fecha,
                            'K6EDTE' => $fefin,
                            'K6DDTE' => $hoy,
                            'K6DSHT' => 'D',
                            'K6PFQY' => $val,
                            'K6CUSR' => 'LXSECOFR',
                            'K6CCDT' => $load,
                            'K6CCTM' => $hora,
                            'K6FIL1' => '',
                            'K6FIL2' => '',

                        ]);


                    $hoy = date('Ymd', strtotime($hoy . '+1 day'));


            }else{



            }
        }
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

    // $plan = IPB::query()
    //     ->select(['IPROD'])
    //     ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
    //     ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
    //     ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
    //     ->where([
    //         ['LX834F01.IIM.IBUYC', $CP],
    //         ['IID', '!=', 'IZ'],
    //         ['IMPLC', '!=', 'OBSOLETE'],
    //     ])
    //     ->where(function($query) {
    //         $query->where('ICLAS ', 'F1');
    //               })
    //     ->distinct('IPROD')
    //     ->orderby('IPROD')
    //     ->simplePaginate(10);
}