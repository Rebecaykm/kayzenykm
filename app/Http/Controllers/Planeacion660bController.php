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
            // $datos = self::CargarforcastF1($padres[0], $fecha, $dias);
            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='", $partsrev);
            // return view('planeacion.plancomponente', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => 0, 'tpag' => $total]);
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
                ['FRDTE', '>=', date('Ymd', strtotime($hoy . '-' . 1 . ' day'))],
                ['FRDTE', '<', $totalF],
            ])
            ->get();


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

            if ($valfinales->count() > 0) {
                $total = 0;
                foreach ($valfinales as $reg4) {
                    if ($reg4->MPROD == $prod['IPROD']) {
                        $dia = $reg4->MRDTE;
                        $turno = $reg4->MRCNO;
                        $total = $reg4->MQTY + 0;
                        $valt = substr($turno, 4, 1);
                        $forcastp += ['For' . $dia . $valt => $total];
                        //  dd(  $forcastp, 'fgsdfgbsetbwse',$prod['IPROD']);
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
                        if ($tipo == 'P') {
                            $tipo='E';
                            $plan_dias=(round((($totalP/$diasd)/$prod['IMBOXQ']),0))*$prod['IMBOXQ'];

                            if($plan_dias<$totalP)
                            {
                                $plan_dias1= $plan_dias+$prod['IMBOXQ'];
                            }
                            else{
                                $plan_dias1= $plan_dias;
                            }

                            if($diasd==2)
                            {

                                $dia1 = date('Ymd', strtotime($hoy . '+' . 1 . ' day'));
                                $dia2 = date('Ymd', strtotime($hoy . '+' . 3 . ' day'));

                                $firme += [$tipo . $dia1 . $valt =>  $plan_dias1];

                                $firme += [$tipo . $dia2 . $valt =>  $plan_dias];
                            }else{

                                $dia1 = date('Ymd', strtotime($hoy . '+' . 0 . ' day'));
                                $dia2 = date('Ymd', strtotime($hoy . '+' . 2 . ' day'));
                                $dia3 = date('Ymd', strtotime($hoy . '+' . 4 . ' day'));
                                $firme += [$tipo . $dia1 . $valt =>  $plan_dias1];

                                $firme += [$tipo . $dia2 . $valt =>  $plan_dias];

                                $firme += [$tipo . $dia3 . $valt =>  $plan_dias];

                            }
                            $tfirme = $tfirme + $total;
                        } else {
                            $firme += [$tipo . $dia . $valt => $total];
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
        // // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        // // $query = "CALL LX834OU02.YMP006C";
        // $result = odbc_exec($conn, $query);
        // $array = explode(",", $TP);


        ProductionPlanByArrayMigrationJob::dispatch($datval);


        return redirect()->route('planeacion660.index');

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
