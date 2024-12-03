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

        $dias = 6;
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
            $datos = self::CargarforcastF1($padres[0], $fecha, $dias);
            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='", $partsrev);
             return view('planeacion.plancomponente660', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => 0, 'tpag' => $total]);
        } else {

            $total = 0;
            $datos = self::CargarforcastF1only($plan1, $fecha, $dias,$diasd);
            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='", $partsrev);
            return view('planeacion.planfinal660', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => 0, 'tpag' => $total]);
        }
    }

    function CargarforcastF1only($prods, $hoy, $dias,$diasd)
    {
        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+ 6 day'));
        $finaArra = array_column($prods, 'IPROD');
        $valfinales = KMR::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', date('Ymd', strtotime($hoy . '-' . 2 . ' day')))
            ->where('MRDTE', '<=',$totalF)
            ->wherein("MPROD" ,$finaArra )
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
                ['FRDTE', '>=', date('Ymd', strtotime($hoy . '-' . 4 . ' day'))],
                ['FRDTE', '<', $totalF],
            ])
            ->get();


$contval=0;
        foreach ($prods as $prod) {
            $inF1 = array();
            $padre = [];
            $dia = $hoy;
            $planpadre = [];
            $totalP = 0;
            $tPlan = 0;
            $tfirme = 0;
            $forcastp = [];
            $padre += ['parte' => $prod['IPROD']];
            $firme = [];
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
                        $plan_dias=round(($totalP/$diasd),0);
                        if($diasd==2)
                        {
                            $dia1 = date('Ymd', strtotime($hoy . '+' . 1 . ' day'));
                            $dia2 = date('Ymd', strtotime($hoy . '+' . 3 . ' day'));
                            $firme += ['E'  . $dia1 . $valt =>  $plan_dias];
                            $firme += ['E' . $dia2 . $valt =>  $plan_dias];
                        }else{
                            $dia1 = date('Ymd', strtotime($hoy . '+' . 0 . ' day'));
                            $dia2 = date('Ymd', strtotime($hoy . '+' . 2 . ' day'));
                            $dia3 = date('Ymd', strtotime($hoy . '+' . 4 . ' day'));
                            $firme += ['E' . $dia1 . $valt =>  $plan_dias];
                            $firme += ['E' . $dia2 . $valt =>  $plan_dias];
                            $firme += ['E' . $dia3 . $valt =>  $plan_dias];
                        }
                        $tfirme = $tfirme + $total;
                    }
                }
            }

            if ($valPDp->count() > 0) {//plan de padre

                $total = 0;


                foreach ($valPDp as $reg6) {
                    if ($reg6->FPROD == $prod['IPROD']) {
                        $dia = $reg6->FRDTE;
                        $turno = $reg6->FPCNO;
                        $tipo = $reg6->FTYPE;
                        $total = $reg6->FQTY + 0;
                        $valt =  'D';




                            $firme += [$tipo . $dia . $valt => $total];
                            $tfirme = $tfirme + $total;
                    }

                }
                $planpadre += $firme;
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
            if (count($valPDp) > 0) {
                $firme = [];
                $total = 0;
                foreach ($valPDp as $reg6) {
                    if ($reg6['FPROD'] == $prod['IPROD']) {
                        // if($prod['IPROD']=="BDTS53816                          ")
                        // {
                        //     dd($prod['IPROD'],  $reg6['FRDTE'],
                        //     $turno = $reg6['FPCNO'],
                        //     $tipo = $reg6['FTYPE'],
                        //     $total = $reg6['FQTY']);
                        // }
                        $dia = $reg6['FRDTE'];
                        $turno = $reg6['FPCNO'];
                        $tipo = $reg6['FTYPE'];
                        $total = $reg6['FQTY'] + 0;
                        $valt = substr($turno, 4, 1) ?? 'D';
                        $firme += [$tipo . $dia . $valt => $total];
                        // $planpadre += [$tipo . $dia . $valt => $total];
                        if ($valt == 'P') {
                            $tPlan = $tPlan + $total;
                        } else {

                            $tfirme = $tfirme + $total;
                        }
                    }
                }
                $planpadre += $firme;
            }
            $padre += ['Qty' => $prod['IMBOXQ'] ?? 0];
            $padre += ['typkt' => $prod['IMSPKT'] ?? 'N/A'];
            $padre += ['tPlan' => $tPlan];
            $padre += ['tfirme' => $tfirme];
            $poskwr = array_search($prod['IPROD'], $prowk);
            $padre += ['WRC' => $wk[$poskwr] ?? '202020020202020'];
            $padre += $forcastp;
            $padre += ['E' => $planpadre];
            $inF1 += ['padre' => $padre];
            array_push($totalpa, $inF1);

        }

        return $totalpa;
    }
    public function updateF1(Request $request)
    {

        $inF1 = array();
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $tipo = $request->tipo;
        $WC = $request->SeWC;
        $variables = $request->all();

        $keyes = array_keys($variables);
        $data = explode('/', $keyes[1], 2);
        $dias = 8;
        $fecha = $data[0];

        $hoy = date('Ymd', strtotime($fecha));
        $datas = [];

        $datval = [];
        $datajob = [];
        $datasql = [];
        $CONT = 0;

        foreach ($keyes as $plans) {
            $dfa = [];
            $dfasql = [];

            $inp = explode('/', $plans, 4);

            if (count($inp) >= 3) {

                $WCT = $inp[3];
                $namenA = strtr($inp[0], '_', ' ');
                $turno = $inp[2];
                $load = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $horasql = date('H:i:s', time());
                $fefin = date('Ymd', strtotime($fecha . '+' . $dias - 1 . ' day'));
                $fechasql = date('Ymd', strtotime($inp[1]));

                if (!in_array($namenA,   $datajob)) {
                    array_push($datajob, $namenA);
                    $ar = ["part_number" => $namenA, "date" => $fechasql];
                    array_push($datval, $ar);
                }

                $dfa = [
                    'K6PROD' => $namenA,
                    'K6WRKC' => $WCT,
                    'K6SDTE' => $fecha,
                    'K6EDTE' => $fefin,
                    'K6DDTE' => $inp[1],
                    'K6DSHT' => $turno,
                    'K6PFQY' => $request->$plans,
                    'K6CUSR' => 'LXSECOFR',
                    'K6CCDT' => $load,
                    'K6CCTM' => $hora,
                    'K6FIL1' => '',
                    'K6FIL2' => ''
                ];
                $dfasql = [
                    'K6PROD' => $namenA,
                    'K6WRKC' => $WCT,
                    'K6SDTE' => $fecha,
                    'K6EDTE' => $fefin,
                    'K6DDTE' => $fechasql,
                    'K6DSHT' => $turno,
                    'K6PFQY' => $request->$plans,
                    'K6CUSR' => 'LXSECOFR',
                    'K6CCDT' => $load,
                    'K6CCTM' => $horasql,
                    'K6FIL1' => '',
                    'K6FIL2' => ''
                ];
                array_push($datasql, $dfasql);
                array_push($datas, $dfa);
            }

            if ($CONT == 80) {
                $indata = YK006::query()->insert($datas);
                $insql = LOGSUP::query()->insert($datasql);
                $datas = [];
                $datasql = [];
                $CONT = 0;
            }
            $CONT = $CONT + 1;
        }


        $indata = YK006::query()->insert($datas);
        $indatasql = LOGSUP::query()->insert($datasql);
        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YMP006C";
        $result = odbc_exec($conn, $query);
        $array = explode(",", $TP);


        ProductionPlanByArrayMigrationJob::dispatch($datval);


        return redirect()->route('660.index');

        // $plan1 = IIM::query()
        //     ->select('IPROD', 'IREF04')
        //     ->wherein('IREF04 ', $array)
        //     ->where([
        //         ['IID', '!=', 'IZ'],
        //         ['IMPLC', '!=', 'OBSOLETE'],
        //     ])

        //     ->where('ICLAS', 'F1')
        //     ->distinct('IPROD')
        //     ->get()->toArray();

        // // $datos = self::CargarforcastF1only($plan1, $fecha, $dias);
        // $partsrev = array_column($plan1, 'IPROD');
        // $cadepar = $request->nextp . "and IPROD!=" . implode("' OR  IPROD='", $partsrev);
        // // dd($datos);
        // return view('planeacion.planfinal1', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => $request->paginate, 'tpag' => 0]);
    }

    /**
     * Store a newly created resource in storage.
     */

     function CargarforcastF1($prods, $hoy, $dias)
    {

        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $finaArra = array_column($prods, 'IPROD');
        // $finales = implode("' OR  MPROD='", $finaArra);
        // $finaleskfp = implode("' OR  FPROD='", $finaArra);
        $valfinales = KMR::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $hoy)
            ->where('MRDTE', '<=', $totalF)
            ->where('MTYPE', '=', 'F')
            ->wherein("MPROD", $finaArra)
            ->get();

        $valPDp = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->wherein("FPROD", $finaArra)
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get();


        foreach ($prods as $prod) {
            $Sub = YMCOM::query()
                ->join('LX834F01.IIM', 'MCCPRO', '=', 'IPROD')
                ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
                ->where([
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],
                ])
                ->whereraw("(MCFPRO='" . $prod['IPROD'] . "') AND  (MCCCLS='M2' or  MCCCLS='M3' or  MCCCLS='M4')")

                ->get()->toarray();

            if (count($Sub) == 0) {
                $datossub = [];
            } else {
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
                $padre += ['total' => $totalP];
                if ($valPDp->count() > 0) {
                    $total = 0;
                    foreach ($valPDp as $reg6) {
                        if ($reg6->FPROD == $prod['IPROD']) {
                            $dia = $reg6->FRDTE;
                            $turno = $reg6->FPCNO;
                            $tipo = $reg6->FTYPE;
                            $total = $reg6->FQTY + 0;
                            $valt = substr($turno, 4, 1);
                            $planpadre += [$tipo . $dia . $valt => $total];
                            if ($valt == 'P') {
                                $tPlan = $tPlan + $total;
                            } else {
                                $tfirme = $tfirme + $total;
                            }
                        }
                    }
                }
                $padre += ['tPlan' => $tPlan];
                $padre += ['tfirme' => $tfirme];
                $padre += $forcastp;
                $padre += $planpadre;
                // dd( $padre);
                $inF1 += ['padre' => $padre];

                $datossub = self::Cargarforcast($prod['IPROD'], $hoy, $dias, $forcastp);
                $inF1 += ['hijos' => $datossub];
                array_push($totalpa, $inF1);
            }
        }


        return $totalpa;
    }

    function Cargarforcast($prod1, $hoy, $dias, $valDp)
    {
        //  $Sub = self::cargar($prod1);
        $Sub = YMCOM::query()
            ->join('LX834F01.IIM', 'MCCPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereraw("(MCFPRO='" . $prod1 . "') AND  (MCCCLS='M2' or  MCCCLS='M3' or  MCCCLS='M4')")
            ->get()->toarray();


        $total = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $sub1 = array_column($Sub, 'MCCPRO');
        $cadsubsPlan = implode("' OR  FPROD='", $sub1);
        $child = implode("' OR  MCCPRO='", $sub1);
        $cadsubKMR = implode("' OR  MPROD='", $sub1);
        $cadsubswrk = implode("' OR  RPROD='", $sub1);
        $Qa = implode("' OR  IPROD='", $sub1);
        // agregar niveles------------------------------------------------------------------------------------------------



        $child_leven = YMWEY::query()
            ->join('LX834F01.IIM', 'IPROD', '=', 'ZEITE')
            ->select('ZEITE', 'ZEQREQ', 'ZEID', 'ZELEVE ')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereIN("IPROD",$sub1 )
            ->get();
//final de agregar niveles ------------------------------------------------------
        $KMRFINAL = YMCOM::query()
            ->join('LX834F01.IIM', 'MCCPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS', 'MCQREQ ')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereraw("(MCCPRO='" . $child . "') AND ( MCFCLS='F1')")
            ->get()->toarray();

        $FINALLIST = array_column($KMRFINAL, 'MCFPRO');
        $FINALMCPRO = array_column($KMRFINAL, 'MCCPRO');
        $FINALCALS = array_column($KMRFINAL, 'MCFCLS');
        $FINALREQ = array_column($KMRFINAL, 'MCQREQ');
        $FINALKMR = implode("' OR  MPROD='", $FINALLIST);

        $RKMRfinal = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD,MTYPE')
            ->whereraw("(MPROD='" . $FINALKMR . "')")
            ->where([
                ['MRDTE', '>=', $hoy],
                ['MRDTE', '<', $totalF],
                ['MTYPE', '=', 'F'],
            ])->groupBy('MRDTE', 'MRCNO', 'MPROD', 'MTYPE')
            ->get()->toarray();


        $valPDpadres = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->wherein('FPROD', array_column($KMRFINAL, 'MCFPRO'))
            ->where([
                ['FRDTE', '>=', date('Ymd', strtotime($hoy . '-' . 4 . ' day'))],
                ['FRDTE', '<', $totalF],
                ['FTYPE', '=', 'F'],
            ])
            ->orderby('FPROD', 'DESC')
            ->get()->toarray();


        $KFPprod = array_column($valPDpadres, 'FPROD');
        $KFPmtype = array_column($valPDpadres, 'FPCNO');
        $KFPfecha = array_column($valPDpadres, 'FRDTE');
        $KFPMtotal = array_column($valPDpadres, 'FQTY');
        $kftype = array_column($valPDpadres, 'FTYPE');

        $kmrprod = array_column($RKMRfinal, 'MPROD');
        $kmrmtype = array_column($RKMRfinal, 'MRCNO');
        $KMRfecha = array_column($RKMRfinal, 'MRDTE');
        $KMRMtotal = array_column($RKMRfinal, 'TOTAL');
        $KtYPE = array_column($RKMRfinal, 'MTYPE');

        // ------------------------------------------------------------------------------------pADRES
        $KMRPARENT = YMCOM::query()
            ->join('LX834F01.IIM', 'MCFPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS', 'IID', 'IMPLC')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereraw("(MCCPRO='" . $child . "') AND (MCFCLS='M2' or  MCFCLS='M3' or  MCFCLS='M4') AND (IID != 'IZ' AND IMPLC != 'OBSOLETE') ")
            ->get()->toarray();

        $kmrmccprod = array_column($KMRPARENT, 'MCCPRO');
        $kmrmcfprod = array_column($KMRPARENT, 'MCFPRO');
        $KMRMCFCLS = array_column($KMRPARENT, 'MCFCLS');
        $PADREKMR = implode("' OR  MPROD='", $kmrmcfprod);

        $RKMR = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD,MTYPE')
            ->whereraw("(MPROD='" . $PADREKMR . "')")
            ->where([
                ['MRDTE', '>=', $hoy],
                ['MRDTE', '<', $totalF],
            ])->groupBy('MRDTE', 'MRCNO', 'MPROD', 'MTYPE')
            ->get()->toarray();

        // -----------------------------------------FIRME PLAN
        $valPD = KFP::query()
            ->select('FPROD', 'FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->whereraw("(FPROD='" . $cadsubsPlan . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();


        $cadsubssh = implode("' OR  SPROD='", $sub1);
        $valSD = FSO::query()
            ->select('SPROD', 'SDDTE', 'SQREQ', 'SOCNO')
            ->whereraw("(SPROD='" . $cadsubssh . "')")
            ->where('SDDTE', '>=', $hoy)
            ->where('SDDTE', '<', $totalF)
            ->get()->toarray();


        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN', 'IMSPKT')
            ->whereraw("(IPROD='" . $Qa . "')")
            ->get()->toArray();

        $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" . $cadsubswrk . "')")
            ->get()->toarray();

        $prowk = array_column($WCT, 'RPROD');
        $prowrok = array_column($WCT, 'RWRKC');

        $prodcqa = array_column($cond, 'IPROD');
        $pqa = array_column($cond, 'IMBOXQ');
        $minba = array_column($cond, 'IMIN');
        $typkt = array_column($cond, 'IMSPKT');
        $sepa = [];


        foreach ($sub1 as $subs) {


            $padreskmr = [];
            $finaleskmr = [];
            $finaleskmrQTY = [];
            $finalkmrQTY = [];
            $numpar = [];
            $numpaplan = [];
            $total = 0;
            $req = 0;
            while (($key5 = array_search($subs,  $FINALMCPRO)) !== false) {
                $req = 0 + $FINALREQ[$key5];

                array_push($finaleskmr, $FINALLIST[$key5]);
                array_push($finaleskmrQTY, $FINALLIST[$key5] . "/REQ:" . $req);

                unset($FINALLIST[$key5]);
                unset($FINALMCPRO[$key5]);
                unset($FINALCALS[$key5]);
                unset($FINALREQ[$key5]);
            }

            while (($key2 = array_search($subs, $kmrmccprod)) !== false) {
                if ($kmrmcfprod[$key2] != $subs) {
                    array_push($padreskmr, $kmrmcfprod[$key2]);
                }
                unset($kmrmccprod[$key2]);
                unset($KMRMCFCLS[$key2]);
                unset($kmrmcfprod[$key2]);
            }

            $FINALLIST = array_column($KMRFINAL, 'MCFPRO');
            $FINALMCPRO = array_column($KMRFINAL, 'MCCPRO');
            $FINALCALS = array_column($KMRFINAL, 'MCFCLS');
            $FINALREQ = array_column($KMRFINAL, 'MCQREQ');
            $FINALKMR = implode("' OR  MPROD='", $FINALLIST);

            $contpadres = count($padreskmr);
            $contF1 = count($finaleskmr);

            if ($contF1 >= 1) {

                $texfinal = implode(',' . '<br> ',    $finaleskmrQTY);

                $cadfinal = implode("' OR  MPROD='", $finaleskmr);
                // $cadsubsL = implode("' OR  LPROD='", $padreskmr );
            } else {
                $texfinal = $finaleskmr[0] ?? '';
                // $cadsubsL = $$padreskmr[0];
                $cadfinal = $finaleskmr[0] ?? '';
            }
            if ($contpadres >= 1) {

                $texpadre = implode(',' . '<br> ', $padreskmr);

                $cadfinal = implode("' OR  MPROD='", $padreskmr);
                // $cadsubsL = implode("' OR  LPROD='", $padreskmr );
            } else {
                $texpadre = $padreskmr[0] ?? '';
                // $cadsubsL = $$padreskmr[0];
                $texpadre = $padreskmr[0] ?? '';
            }
            $forcast = [];
            $Tshop = 0;
            $Tplan = 0;
            $Tfirme = 0;
            $total = 0;
            // ------------------------------- sacar valores KMR

            foreach ($padreskmr as $P1) {
                $kmrpad = array_column($RKMR, 'MPROD');
                $kmrpadno = array_column($RKMR, 'MRCNO');
                $KMRpaddat = array_column($RKMR, 'MRDTE');
                $KMRmtoalpa = array_column($RKMR, 'TOTAL');
                while (($key3 = array_search($P1, $kmrpad)) !== false) {
                    $dia = $KMRpaddat[$key3];
                    $turno = $kmrpadno[$key3];
                    $total = $KMRmtoalpa[$key3] + 0;
                    $valt = substr($turno, 4, 1);
                    if (array_key_exists('KMRS' . $dia . $valt, $forcast) !== false) {
                        $total = $forcast['KMRS' . $dia . $valt] + $total;
                        $forcast['KMRS' . $dia . $valt] = $total;
                    } else {
                        $forcast += ['KMRS' . $dia . $valt => $total];
                    }

                    unset($kmrpad[$key3]);
                    unset($kmrpadno[$key3]);
                    unset($KMRpaddat[$key3]);
                    unset($KMRmtoalpa[$key3]);
                }
            }


            foreach ($finaleskmr as $F1) {
                $total = 0;

                while (($key3 = array_search($F1,    $KFPprod)) !== false) {
                    $dia =  $KFPfecha[$key3];
                    $turno = $KFPmtype[$key3];
                    $total = $KFPMtotal[$key3] + 0;
                    $valt = substr($turno, 4, 1);


                    if (array_key_exists('kfp' . $dia . $valt, $forcast) !== false) {
                        $total = $forcast['kfp' . $dia . $valt] + $total;
                        $forcast['kfp' . $dia . $valt] = $total;
                    } else {
                        $forcast  += ['kfp' . $dia . $valt => $total];
                    }

                    unset($KFPprod[$key3]);
                    unset($KFPmtype[$key3]);
                    unset($KFPfecha[$key3]);
                    unset($KFPMtotal[$key3]);
                }
            }

            $KFPprod = array_column($valPDpadres, 'FPROD');
            $KFPmtype = array_column($valPDpadres, 'FPCNO');
            $KFPfecha = array_column($valPDpadres, 'FRDTE');
            $KFPMtotal = array_column($valPDpadres, 'FQTY');
            $kftype = array_column($valPDpadres, 'FTYPE');
            $total = 0;
            foreach ($finaleskmr as $F1) {

                while (($key3 = array_search($F1, $kmrprod)) !== false) {
                    $dia = $KMRfecha[$key3];
                    $turno = $kmrmtype[$key3];
                    $total = $KMRMtotal[$key3] + 0;
                    $valt = substr($turno, 4, 1);
                    $ktype = $KtYPE[$key3];

                    if (array_key_exists('kmr' . $dia . $valt, $forcast) !== false) {
                        $total = $forcast['kmr' . $dia . $valt] + $total;
                        $forcast['kmr' . $dia . $valt] = $total;
                    } else {
                        $forcast += ['kmr' . $dia . $valt => $total];
                    }


                    unset($kmrprod[$key3]);
                    unset($kmrmtype[$key3]);
                    unset($KMRfecha[$key3]);
                    unset($KMRMtotal[$key3]);
                }
            }
            $kmrprod = array_column($RKMRfinal, 'MPROD');
            $kmrmtype = array_column($RKMRfinal, 'MRCNO');
            $KMRfecha = array_column($RKMRfinal, 'MRDTE');
            $KMRMtotal = array_column($RKMRfinal, 'TOTAL');
            $KtYPE = array_column($RKMRfinal, 'MTYPE');

            $total = 0;
            foreach ($valPD as $reg3) {
                if ($reg3['FPROD'] == $subs) {
                    $dia = $reg3['FRDTE'];
                    $turno = $reg3['FPCNO'];
                    $tipo = $reg3['FTYPE'];
                    $total = $reg3['FQTY'] + 0;
                    $valt = substr($turno, 4, 1);
                    $numpaplan += [$tipo . $dia . $valt => $total];
                    if ($tipo == 'P') {
                        $Tplan = $Tplan + $total;
                    } else {
                        $Tfirme = $Tfirme + $total;
                    }
                }
            }
            $total = 0;
            foreach ($valSD as $reg4) {
                if ($reg4['SPROD'] == $subs) {
                    $dia = $reg4['SDDTE'];
                    $turno = $reg4['SOCNO'];
                    $total = $reg4['SQREQ'] + 0;
                    $valt = substr($turno, 4, 1);
                    $numpaplan += ['S' . $dia . $valt => $total];
                    $Tshop = $Tshop + $total;
                }
            }
            $total = 0;
            $Tshopkmr = 0;

            $pos = array_search($subs, $prodcqa);
            $poskwr = array_search($subs, $prowk);
            $level = $child_leven->first(function ($item) use ($subs) {
                return $item->ZEITE === $subs;
            });


            $numpar += ['sub' => $subs, 'plan' => $numpaplan, 'padres' => $texfinal, 'forcast' => $forcast, 'Qty' => $pqa[$pos] ?? 0,
             'minbal' => $minba[$pos] ?? 0, 'typkt' => $typkt[$pos] ?? 'N/A', 'wrk' => $prowrok[$poskwr] ?? 0, 'Tshop' => $Tshop,
             'Tplan' => $Tplan, 'Tfirme' => $Tfirme, 'KMRpadres' => $texpadre ?? 0, 'Totalpadres' => $Tshopkmr,'level'=>$level->ZELEVE];

            $sepa += [$subs => $numpar];

        }


        return $sepa;
    }

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

    public function update(Request $request)
    {

        $inF1 = array();
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $tipo = $request->tipo;
        $WC = $request->SeWC;
        $variables = $request->all();

        $keyes = array_keys($variables);
        $data = explode('/', $keyes[1], 2);
        $dias = 8;
        $fecha = $data[0];
        $hoy = date('Ymd', strtotime($fecha));
        $datas = [];
        $datasql = [];
        $datajob = [];
        $datval = [];
        $CONT = 0;
        foreach ($keyes as $plans) {
            $dfa = [];
            $inp = explode('/', $plans, 4);
            if (count($inp) >= 3) {
                $WCT = $inp[3];
                $dfasql = [];

                $namenA = strtr($inp[0], '_', ' ');

                $turno = $inp[2];
                $load = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $horasql = date('H:i:s', time());
                $fefin = date('Ymd', strtotime($fecha . '+' . $dias - 1 . ' day'));
                $fechasql = date('Ymd', strtotime($inp[1]));
                if (!in_array($namenA,   $datajob)) {
                    array_push($datajob, $namenA);
                    $ar = ["part_number" => $namenA, "date" => $fechasql];
                    array_push($datval, $ar);
                }

                $dfa = [
                    'K6PROD' => $namenA,
                    'K6WRKC' => $WCT,
                    'K6SDTE' => $fecha,
                    'K6EDTE' => $fefin,
                    'K6DDTE' => $inp[1],
                    'K6DSHT' => $turno,
                    'K6PFQY' => $request->$plans,
                    'K6CUSR' => 'LXSECOFR',
                    'K6CCDT' => $load,
                    'K6CCTM' => $hora,
                    'K6FIL1' => '',
                    'K6FIL2' => ''
                ];
                $dfasql = [
                    'K6PROD' => $namenA,
                    'K6WRKC' => $WCT,
                    'K6SDTE' => $fecha,
                    'K6EDTE' => $fefin,
                    'K6DDTE' => $fechasql,
                    'K6DSHT' => $turno,
                    'K6PFQY' => $request->$plans,
                    'K6CUSR' => 'LXSECOFR',
                    'K6CCDT' => $load,
                    'K6CCTM' => $horasql,
                    'K6FIL1' => '',
                    'K6FIL2' => ''
                ];
                array_push($datasql, $dfasql);
                array_push($datas, $dfa);
            }
            if ($CONT == 50) {
                $indata = YK006::query()->insert($datas);
                $insql = LOGSUP::query()->insert($datasql);
                $datas = [];
                $datasql = [];
                $CONT = 0;
            }
            $CONT = $CONT + 1;
        }

        $indata = YK006::query()->insert($datas);
        $indatasql = LOGSUP::query()->insert($datasql);

        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU.YMP006C";

        $result = odbc_exec($conn, $query);
        $array = explode(",", $TP);

        ProductionPlanByArrayMigrationJob::dispatch($datval);


        $plan1 = IIM::query()
            ->select('IPROD', 'IREF04')
            ->wherein('IREF04 ', $array)
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();

        $padres = array_chunk($plan1, 10);
        $total = count($padres);
        $datos = self::CargarforcastF1($padres[$request->paginate], $fecha, $dias);

        $partsrev = array_column($plan1, 'IPROD');
        $cadepar = $request->nextp . "and IPROD!=" . implode("' OR  IPROD='", $partsrev);

        return view('planeacion.plancomponente660', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => $request->paginate, 'tpag' => $total]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
