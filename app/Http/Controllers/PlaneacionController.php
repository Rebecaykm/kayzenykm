<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LWK;
use App\Models\IPB;
use App\Models\KMR;
use App\Models\kFP;
use App\Models\frt;
use App\Models\ZCC;
use App\Models\YK006;
use App\Models\Structure;
use Carbon\Carbon;
use Symfony\Component\VarDumper\Caster\FrameStub;

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
        $TP = $request->SeProject ;
        $CP = $request->SePC;
        $WC = $request->SeWC;


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
                ->simplePaginate(2)->withQueryString();



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

        $TP = $request->SeProject;
        $CP = $request->SePC;
        $WC = $request->SeWC;

        $variables = $request->all();
        $keyes= array_keys($variables);
        $data=explode('/', $keyes[1], 2);
        $dias =  $data[1];
        $fecha =  $data[0];
        foreach ($keyes as $plans) {
            $inp = explode('/', $plans, 3);
            if (count($inp) >= 3) {
                $namenA = strtr($inp[0], '_', ' ');
                $hoy = date('Ymd', strtotime('now'));
                $load = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $fefin = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
                $WCT= Frt::query()
                ->select('RWRKC','RPROD')
                ->where('RPROD', $namenA)
                ->value('RWRKC');
                        $data = YK006::query()->insert([
                            'K6PROD' => $namenA,
                            'K6WRKC' => $WCT,
                            'K6SDTE' => $fecha,
                            'K6EDTE' => $fefin,
                            'K6DDTE' => $hoy,
                            'K6DSHT' => 'D',
                            'K6PFQY' => $request->$plans,
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
        $plan = IPB::query()
                ->select('IPROD', 'ICLAS', 'IMBOXQ')
                ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
                ->where([
                    ['IREF04', 'like', '%' . $TP . '%'],
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],

                ])
                ->where(function ($query) {
                    $query->where('ICLAS ', 'F1');
                })
                ->distinct('IPROD')
                ->simplePaginate(2);

        return view('planeacion.plancomponente', [ 'plan' => $plan,'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
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
