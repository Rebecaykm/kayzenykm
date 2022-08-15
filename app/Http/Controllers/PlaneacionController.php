<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LWK;
use App\Models\IPB;
use App\Models\KMR;


class PlaneacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return\Illuminate\Http\Response
     */

    public $plan=null;
    public function index()
    {
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

        $TP=$request->SeTP;
        $CP=$request->SePC;
        $WC=$request->SeWC;
                if($TP==1)
                {
                    $plan = IPB::query()
                    ->select('IPROD','BPROD','BCLAS','BCHLD','BCLAC','ICLAS','IREF04','IID','IMPLC')
                    ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
                    ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
                    ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
                    ->join('LX834F01.MBM', 'LX834F01.MBM.BCHLD', '=', 'LX834F01.IIM.IPROD ')
                    ->where('PBPBC', '=', $CP)
                    ->where('IID', '!=', 'IZ')
                    ->where('IMPLC', '!=', 'OBSOLETE')
                    ->where('ICLAS ', '=', 'F1')
                    ->distinct('IPROD')
                    ->limit(15)
                    ->get();

                }
                else{
                    $plan = IPB::query()
                    ->select('IPROD','BPROD','BCLAS','BCHLD','BCLAC','ICLAS','IREF04','IID','IMPLC')
                    ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
                    ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
                    ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
                    ->join('LX834F01.MBM', 'LX834F01.MBM.BCHLD', '=', 'LX834F01.IIM.IPROD ')
                    ->where('PBPBC', '=', $CP)
                    ->where('IID', '!=', 'IZ')
                    ->where('IMPLC', '!=', 'OBSOLETE')
                    ->where('ICLAS ', '=', 'M2')
                    ->orwhere('ICLAS ', '=', 'M3')
                    ->orwhere('ICLAS ', '=', 'M4')
                    ->distinct('IPROD')
                    ->limit(15)
                    ->get();
                }




        return view('planeacion.plan', [ 'plan'=>$plan,'tp'=>$TP,'cp'=>$CP,'wc'=>$WC]);
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
class miguel{
    function contar($var1){
        dd('la variable es'.$var1);
    }
}
