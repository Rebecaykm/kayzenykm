<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KMR;
use App\Models\KFP;
use App\Models\FRT;
use App\Models\IIM;
use App\Models\LOGSUP;
use App\Models\YMWEY;
use App\Models\ECL;
use App\Models\YMCOM;
use App\Models\FSO;
use App\Models\YK006;
use Carbon\Carbon;
use App\Exports\PlanExport;
use App\Exports\PlanFinalExport;
use App\Exports\PlansubExport;
use App\Jobs\ProductionPlanByArrayMigrationJob;
use Maatwebsite\Excel\Facades\Excel;

class Planeacion660bController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        $dias = $request->NP ?? '*';
        $fecha = $request->Seproject ?? '*';
        $plan = '';
        $TP = 'NO';
        $CP = '';
        $WC = '';
        $WCs = [];
        return view('planeacion.660B', ['LWK' => $WCs]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $tipo = $request->Planeacion;

        $dias = 8;
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $diasd=$request->DiasD;

        $CP = $request->SePC;
        $WC = $request->SeWC;
        $array = explode(",", $TP);
        $plan1 = IIM::query()
            ->select('IPROD', 'IREF04','IMBOXQ','IMSPKT')
            ->wherein('IREF04 ', $array)
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])

            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();
        $padres = array_chunk($plan1, 10);
        if ($tipo == 2) {
            $total = count($padres);
            // $datos = self::CargarforcastF1($padres[0], $fecha, $dias);
            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='", $partsrev);
            // return view('planeacion.plancomponente', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => 0, 'tpag' => $total]);
        } else {

            $total = 0;
            $datos = self::CargarforcastF1only($plan1, $fecha, $dias,$diasd);
            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='", $partsrev);
            return view('planeacion.planfinal1', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => 0, 'tpag' => $total]);
        }
    }

    function CargarforcastF1only($prods, $hoy, $dias,$diasd)
    {
        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));

        $finaArra = array_column($prods, 'IPROD');
        $finales = implode("' OR  MPROD='", $finaArra);
        // $finalesecl = implode("' OR  LPROD='", $finaArra);
        // $finaleswrk = implode("' OR  RPROD='", $finaArra);
        // $Qa = implode("' OR  IPROD='", $finaArra);

        $valfinales = KMR::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $hoy)
            ->where('MRDTE', '<', $totalF)
            ->where('MTYPE', '=', 'F')
            ->whereraw("(MPROD='" . $finales . "')")
            ->get();
        $MBMS = ECL::query()
            ->selectRaw('LSDTE, SUM(LQORD) as Total,CLCNO,LPROD ')
            ->wherein("LPROD" ,$finaArra )
            ->where([
                ['LSDTE', '>=', $hoy],
                ['LSDTE', '<', $totalF],
            ])
            ->groupBy('LPROD', 'LSDTE', 'CLCNO')
            ->get();
        // $cond = IIM::query()
        //     ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN', 'IMSPKT')
        //     ->whereraw("(IPROD='" . $Qa . "')")
        //     ->get()->toArray();

        // $prodcqa = array_column($cond, 'IPROD');
        // $pqa = array_column($cond, 'IMBOXQ');
        // $typkt = array_column($cond, 'IMSPKT');


        $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->wherein("RPROD",$finaArra )
            ->get()->toarray();


        $prowk = array_column($WCT, 'RPROD');
        $wk = array_column($WCT, 'RWRKC');
        $valPDp = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->wherein("FPROD" , $finaArra)
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get();

        foreach ($prods as $prod) {


            $inF1 = array();
            $padre = [];
            $dia = $hoy;
            $connt = 1;
            $i = 0;
            $planpadre = [];
            $totalP = 0;
            $tPlan = 0;
            $tfirme = 0;
            $forcastp = [];
            $padre += ['parte' => $prod['IPROD']];
            //obtener forcaste
            if ($valfinales->count() > 0) {
                $total = 0;
                foreach ($valfinales as $reg4) {
                    if ($reg4->MPROD == $prod['IPROD']) {
                        $dia = $reg4->MRDTE;
                        $turno = $reg4->MRCNO;
                        $total = $reg4->MQTY + 0;
                        $valt = substr($turno, 4, 1);
                        $forcastp += ['For' . $dia . $valt => $total];
                        $totalP = $totalP + $total;
                    }
                }
            }
            if (count($MBMS) > 0) {
                foreach ($MBMS as $reg1) {
                    if ($reg1->LPROD == $prod['IPROD']) {
                        $dia = $reg1->LSDTE;
                        $turno = $reg1->CLCNO;
                        $total = $reg1->TOTAL + 0;
                        $valt = substr($turno, 4, 1);
                        $forcastp += ['ecl' . $dia . $valt => $total];
                    }
                }
            }

            $padre += ['total' => $totalP];

            if ($valPDp->count() > 0) {//plan de padre
                $firme = [];
                $total = 0;

                foreach ($valPDp as $reg6) {
                    if ($reg6->FPROD == $prod['IPROD']) {

                        $dia = $reg6->FRDTE;
                        $turno = $reg6->FPCNO;
                        $tipo = $reg6->FTYPE;
                        $total = $reg6->FQTY + 0;
                        $valt = substr($turno, 4, 1) ?? 'D';
                        $firme += [$tipo . $dia . $valt => $total];

                        if ($tipo == 'P') {

//plan /qbox

                            $plan_dias=(round((round($totalP/3, 0)/$prod['IMBOXQ']),0))*$prod['IMBOXQ'];
                            if($plan_dias<$totalP)
                            {
                                $plan_dias+=$prod['IMBOXQ'];
                            }



                            // dd( $totalP,$prod['IMBOXQ'],$plan_dias,$diasd);
                            // $tPlan = $tPlan + $total;
                        } else {

                            $firme += [$tipo . $dia . $valt => $total];

                            $tfirme = $tfirme + $total;
                        }
                    }
                }
                $planpadre += $firme;
            }
// dd($padre );
            $padre += ['Qty' => $prod['IMBOXQ'] ?? 0];
            $padre += ['typkt' => $prod['IMSPKT'] ?? 'N/A'];
            $padre += ['tPlan' => $tPlan];
            $padre += ['tfirme' => $tfirme];
            $poskwr = array_search($prod['IPROD'], $prowk);
            $padre += ['WRC' => $wk[$poskwr] ?? '202020020202020'];
            $padre += $forcastp;
            $padre += ['F' => $planpadre];
            // dd( $padre);
            $inF1 += ['padre' => $padre];

            array_push($totalpa, $inF1);
        }

        return $totalpa;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
