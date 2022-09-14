<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LWK;
use App\Models\IPB;
use App\Models\KMR;
use App\Models\kFP;
use App\Models\YK006;
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
        $this->WCs = LWK::query()
            ->select('WID', 'WDEPT', 'WWRKC', 'WDESC')
            ->where('WID', '=', 'WK')
            ->Where('WDEPT', '=', '1200')
            ->orWhere('WDEPT', '=', '1300')
            ->orWhere('WDEPT', '=', '1400')
            ->orderBy('WDESC', 'ASC')
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
        $TP = $request->SeTP;
        $CP = $request->SePC;

        $WC = $request->SeWC;



        if ($TP == 1) {
            $plan = IPB::query()
                ->select(['IPROD'])
                ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
                ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
                ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
                ->where('IBUYC', '=', $CP)
                ->where('IID', '!=', 'IZ')
                ->where('IMPLC', '!=', 'OBSOLETE')
                ->where('ICLAS ', '=', 'F1')
                ->distinct('IPROD')
                ->orderby('IPROD')
                ->simplePaginate(10)
               ;

        } else {
            $plan = IPB::query()
                ->select('IPROD', 'ICLAS', 'IREF04', 'IID', 'IMPLC','IBUYC','IMPLC')
                ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
                ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
                ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
                ->where([
                    ['LX834F01.IIM.IBUYC', '=', 'CB4'],
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],
                    ['ICLAS ', '=', 'M2','or',
                    'ICLAS ', '=', 'M3','or',
                    'ICLAS ', '=', 'M4'],

                ])
                ->distinct('IPROD')
                ->simplePaginate(10);
                dd($plan);

        }

        if ($TP == 1) {
            return view('planeacion.planfinal', ['plan' => $plan, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
        } else {
            return view('planeacion.plancomponente', ['ipb' => $CP, 'plan' => $plan, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
        }



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
        $TP = $request->SeTP;
        $CP = $request->SePC;
        $WC = $request->SeWC;
        $fecha = $request->fecha;
        $dias = $request->dias;


        $plan = IPB::query()
            ->select(['IPROD', 'IVEND', 'IVEND', 'IPURC', 'IBUYC', 'ICLAS', 'IMRP','WWRKC'])
            ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
            ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
            ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
            ->where('PBPBC', '=', $CP)
            ->where('IID', '!=', 'IZ')
            ->where('IMPLC', '!=', 'OBSOLETE')
            ->where('ICLAS ', '=', 'F1')
            ->distinct('IPROD')
            ->orderby('IPROD')
            ->get();


        foreach ($plan as $plans) {

            $hoy = date('Ymd', strtotime('now'));
            $cont=0;
            $fefin=date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
            While($cont<=$dias)
            {
                $part = str_replace(" ", "_", $plans->IPROD);
                $inpD = $part . "/" . $fecha . "/D";
                $inpN = $part . "/" . $fecha . "/N";
                if(isset($request->$inpD) && isset($request->$inpN)){
                    $val = $request->$inpD;
                    $valN = $request->$inpN;
                    $hora = date('His', time());
                    $hoy=date('Ymd', strtotime($hoy . '+1 day'));

                    $cadena=$plans->IPROD.'/'.$plans->WWRKC.'/'.$fecha.'/'.$fefin.'/'.$hoy.'/'.'D'.'/'.$val;
                    echo $cadena;
                    echo '<br>';
                    $cadenaN=$plans->IPROD.'/'.$plans->WWRKC.'/'.$fecha.'/'.$fefin.'/'.$hoy.'/'.'N'.'/'.$valN;
                   echo $cadenaN;
                   echo '<br>';
                }

                $cont++;
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
}
