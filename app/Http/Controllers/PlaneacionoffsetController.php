<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KMR;
use App\Models\KFP;
use App\Models\FRT;
use App\Models\IIM;
use App\Models\LOGSUP;
use App\Models\YKPLN;
use App\Models\ECL;
use App\Models\YMCOM;
use App\Models\FSO;
use App\Models\YK006;
use App\Models\YK0062;
use Carbon\Carbon;
use App\Exports\PlanExport;
use App\Exports\PlanExportOS;
use App\Exports\PlanFinalExport;
use App\Exports\PlansubExport;
use App\Exports\PlansubExportOS;
use App\Jobs\ProductionPlanByArrayMigrationJob;
use Maatwebsite\Excel\Facades\Excel;


class PlaneacionoffsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return\Illuminate\Http\Response
     */

    public $plan = null;

    public function index(Request $request)
    {
        $dias = $request->NP ?? '*';
        $fecha = $request->Seproject ?? '*';
        $plan = '';
        $TP = 'NO';
        $CP = '';
        $WC = '';
        $WCs = [];
        return view('planeacion.index', ['LWK' => $WCs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create(Request $request)
    {
        $tipo = $request->Planeacion;
        $dias = 21;
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $WC = $request->SeWC;
        $array = explode(",", $TP);
        if ($tipo == 2) {
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
            $datos = self::CargarforcastF1($padres[0], $fecha, $dias);
            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='", $partsrev);
            // dd( $cadepar );
            return view('planeacion.plancomponenteOSmmmmm', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => 0, 'tpag' => $total]);
        } else {
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
            $total = 0;
            $datos = self::CargarforcastF1only($plan1, $fecha, $dias);
            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='", $partsrev);
            return view('planeacion.planfinal1', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => 0, 'tpag' => $total]);
        }
    }


    public function siguiente(Request $request)
    {

        $tipo = $request->SeProject;
        $dias = 8;
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $WC = $request->SeWC;
        $array = explode(",", $TP);
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
        $partsrev = array_column($plan1, 'IPROD');
        $total = count($padres) - 1;
        $datos = self::CargarforcastF1($padres[$request->paginate], $fecha, $dias);
        $cadepar = $request->nextp . "and IPROD!=" . implode("' OR  IPROD='", $partsrev);
        return view('planeacion.plancomponenteOSmmmmm', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $request->fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => $request->paginate, 'tpag' => $total]);
    }

    public function export(Request $request)
    {

        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $fechaFin = $request->fechaFin != '' ? Carbon::parse($request->fechaFin)->format('Ymd') : Carbon::now()->format('Ymd');


        return Excel::download(new PlanExport($fecha, $fechaFin), 'Planeacion.xlsx');
    }

    public function exportfinal(Request $request)
    {
        $inF1 = array();
        $inF2 = array();
        $dias = $request->dias ?? '6';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $fechaFin = $request->fechaFin != '' ? Carbon::parse($request->fechaFin)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;

        return Excel::download(new PlanFinalExport($fecha, $dias, $TP), 'PartesfinalesP' . $TP . '.xlsx');
    }

    public function exportsubcomponentes(Request $request)
    {
        $inF1 = array();
        $inF2 = array();
        $dias = $request->dias ?? '6';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $fechaFin = $request->fechaFin != '' ? Carbon::parse($request->fechaFin)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $pro = '';
        switch ($TP) {
            case ('2,12,123,13,20,23,3');
                $pro = 'J03W-G';
                break;

            case ('4,45,47'):
                $pro = 'J59W';
                break;

            case ('5,56,57'):
                $pro = 'J59J';
                break;
            case ('7,79,710'):
                $pro = 'J34A';
                break;
            case ('9,79'):
                $pro = 'J34H';
                break;
            case ('10,710'):
                $pro = 'J34X';
                break;
            case ('8,811'):
                $pro = '660B';
                break;
            case ('11,811'):
                $pro = '920B';
                break;
        }

        if ($request->Type == 1) {
            return Excel::download(new PlanFinalExport($fecha, $dias, $TP), 'finales_' . $pro . '_' . $fecha . '.xlsx');
        } else {
            return Excel::download(new PlansubExportOS($fecha, $dias, $TP), 'Subcomponentes_' .  $pro . '_' . $fecha . '.xlsx');
        }
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
                $fefin = date('Ymd', strtotime($fecha . '+' . $dias - 2 . ' day'));
                $fechasql = date('Ymd', strtotime($inp[1]));

                // if (!in_array($namenA,   $datajob)) {
                //     array_push($datajob, $namenA);
                //     $ar = ["part_number" => $namenA, "date" => $fechasql];
                //     array_push($datval, $ar);
                // }

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
            if ($CONT == 160) {
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


        //  ProductionPlanByArrayMigrationJob::dispatch($datval);

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

        $datos = self::CargarforcastF1only($plan1, $fecha, $dias);
        $partsrev = array_column($plan1, 'IPROD');
        $cadepar = $request->nextp . "and IPROD!=" . implode("' OR  IPROD='", $partsrev);
        // dd($datos);
        return view('planeacion.planfinal1', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => $request->paginate, 'tpag' => 0]);
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
        $chpart = [];
        $chpartcol = collect();
        foreach ($keyes as $plans) {
            $dfa = [];
            $inp = explode('/', $plans);

            if (count($inp) == 3) {
                if ($inp[0] == 'Che' && $inp[1] == 'on') {
                    $chpart += [$inp[2] => $inp[2]];
                    $chpartcol->put($inp[2], $inp[2]);
                }
            }
        }


        foreach ($keyes as $plans) {
            $dfa = [];
            $inp = explode('/', $plans);


            if (count($inp) >= 3 && $inp[0] != 'Che') {

                $WCT = $inp[3];
                $dfasql = [];
                $indice = $chpartcol->search($inp[0]);

                if ($indice !== false) {
                    // dd('fkmdk');
                    $namenA = strtr($inp[0], '_', ' ');
                    $turno = $inp[2];
                    $load = date('Ymd', strtotime('now'));
                    $hora = date('His', time());
                    $horasql = date('H:i:s', time());
                    $fefin = date('Ymd', strtotime($fecha . '+' . $dias - 2 . ' day'));
                    $fechasql = date('Ymd', strtotime($inp[1]));
                    if (!in_array($namenA,   $datajob)) {
                        array_push($datajob, $namenA);
                        $ar = ["part_number" => $namenA, "date" => $fechasql];
                        array_push($datval, $ar);
                    }

                    $dfa = [
                        'K62PRO' => $namenA,
                        'K62WRK' => $WCT,
                        'K62SDT' => $fecha,
                        'K62EDT' => $fefin,
                        'K62DDT' => $inp[1],
                        'K62DSH' => $turno,
                        'K62PFQ' => $request->$plans,
                        'K62CUS' => 'LXSECOFR',
                        'K62CCD' => $load,
                        'K62CCT' => $hora,
                        'K62FI1' => '',
                        'K62FI2' => ''
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

                    if ($CONT == 80) {
                        $indata = YK0062::query()->insert($datas);
                        $insql = LOGSUP::query()->insert($datasql);
                        $datas = [];
                        $datasql = [];
                        $CONT = 0;
                    }
                    $CONT = $CONT + 1;
                } else {
                }
            }
        }
        $indata = YK0062::query()->insert($datas);
        $indatasql = LOGSUP::query()->insert($datasql);


        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        // $query = "CALL LX834OU02.YMP006C";
        $query = "CALL LX834OU.YMR002C";

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
        return view('planeacion.plancomponenteOSmmmmm', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => $request->paginate, 'tpag' => $total]);
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

    function CargarforcastF1($prods, $hoy, $dias)
    {
        $totalpa = [];
        foreach ($prods as $prod) {
            $Sub = YMCOM::query()
                ->join('LX834F01.IIM', 'MCCPRO', '=', 'IPROD')
                ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
                ->where([
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],
                ])
                ->whereraw("(MCFPRO='" . $prod['IPROD'] . "') AND  (MCCCLS='M2' or  MCCCLS='M3' or  MCCCLS='M4')")
                ->get();

            if (count($Sub) == 0) {
                $datossub = [];
            } else {
                $inF1 = array();
                $padre = [];
                $forcastp = [];
                $padre += ['parte' => $prod['IPROD']];
                $inF1 += ['padre' => $padre];
                $datossub = self::Cargarforcast($prod['IPROD'], $hoy, $dias, $forcastp,    $Sub);
                $inF1 += ['hijos' => $datossub];
                array_push($totalpa, $inF1);
            }
        }
        return $totalpa;
    }


    function Cargarforcast($prod1, $hoy, $dias, $valDp, $Sub)
    {

        $total = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $sub1 = $Sub->pluck('MCCPRO');


        $KMRFINAL = YMCOM::query()
            ->join('LX834F01.IIM', 'MCCPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS', 'MCQREQ ')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])->wherein('MCCPRO', $sub1)
            ->where('MCFCLS', '=', 'F1')
            ->get();

        $RKMRfinal = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD,MTYPE')
            ->whereIN('MPROD', $KMRFINAL->pluck('MCFPRO'))
            ->where([
                ['MRDTE', '>=', $hoy],
                ['MRDTE', '<', $totalF],
                ['MTYPE', '=', 'F'],
            ])->groupBy('MRDTE', 'MRCNO', 'MPROD', 'MTYPE')
            ->get();


        $offset = YKPLN::query()
            ->join('LX834F01.YMWEY', 'PPROD', '=', 'ZEITE')
            ->select('PPROD', 'PRDTE', 'PRSHFT', 'PRREQ', 'PQTY', 'PRPLQ', 'PRCAO', 'ZELEVE')
            ->whereIN('PPROD', $sub1)->where([
                ['PRDTE', '>=', $hoy],
                ['PRDTE', '<', $totalF],
            ])->get();
        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN', 'IMSPKT')
            ->wherein("IPROD", $sub1)
            ->get();

        $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->wherein("RPROD", $sub1)
            ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');


        $coleccion = collect();
        // finales




        foreach ($sub1 as $subs) {
            $numpaplan = [];
            $forcast = [];
            $Tshop = 0;
            $Tplan = 0;
            $Tfirme = 0;
            $total = 0;
            $ofsg = [];
            $level = '';
            $padresub = $KMRFINAL->where('MCCPRO', $subs);
            $kmrpadress = $RKMRfinal->wherein('MPROD', $padresub->pluck('MCFPRO'));

            foreach ($kmrpadress as $kmrp) {
                $turno = $kmrp['MRCNO'];
                $valt = substr($turno, 4, 1);
                $forcast  += ['KMRS' . $kmrp['MRDTE'] . $valt => $kmrp['TOTAL']];
            }
            foreach ($offset as $off) {
                $ofs = [];
                if ($off['PPROD'] == $subs) {
                    $level = preg_replace('/\.\s*/', '', $off['ZELEVE']);;
                    $PRDTE = $off['PRDTE'];
                    $SHIFT = $off['PRSHFT'];
                    if ($SHIFT == 'D' ||  $SHIFT == 'N') {
                        $peof = $off['PRREQ'] + 0; // quantity pequest
                        $reqof = $off['PQTY'] + 0; //quantity requied
                        $reqpla = $off['PRPLQ'] + 0; //quantity planned
                        $carrof = $off['PRCAO'] + 0; //carriover

                        $ofs += ['opreq' . $PRDTE . $SHIFT => $peof];
                        $ofs += ['oqty' . $PRDTE . $SHIFT => $reqof];
                        $ofs += ['oplan' . $PRDTE . $SHIFT => $reqpla];
                        $ofs += ['ocarry' . $PRDTE . $SHIFT => $carrof];

                        $ofsg += $ofs;
                    }
                }
            }

            $total = 0;
            $Tshopkmr = 0;
            $prodcqa = $cond->where('IPROD',$subs);

            $poskwr = array_search($subs, $prowk);


            $coleccion->put($subs, [
                'sub' => $subs,
                'level' => $level,
                'plan' => $numpaplan,
                'padres' => "",
                'forcast' => $forcast,
                'Qty' =>  0,
                'minbal' => 0,
                'typkt' =>  'N/A',
                'wrk' => $prowrok[$poskwr] ?? 0,
                'Tshop' => $Tshop,
                'Tplan' => $Tplan,
                'Tfirme' => $Tfirme,
                'KMRpadres' => $texpadre ?? 0,
                'Totalpadres' => $Tshopkmr,
                'offset' => $ofsg
            ]);

            // $sepa += [$subs => $numpar];
        }


        $sorted = $coleccion->sortBy('level')->values();
        $sepa = $sorted->toArray();


        return $sepa;
    }

    function CargarforcastF1only($prods, $hoy, $dias)
    {
        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $finaArra = array_column($prods, 'IPROD');
        $finales = implode("' OR  MPROD='", $finaArra);
        $finalesecl = implode("' OR  LPROD='", $finaArra);
        $finaleswrk = implode("' OR  RPROD='", $finaArra);
        $Qa = implode("' OR  IPROD='", $finaArra);
        $finaleskfp = implode("' OR  FPROD='", $finaArra);
        $valfinales = KMR::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $hoy)
            ->where('MRDTE', '<', $totalF)
            ->where('MTYPE', '=', 'F')
            ->whereraw("(MPROD='" . $finales . "')")
            ->get()->toarray();

        $MBMS = ECL::query()
            ->selectRaw('LSDTE, SUM(LQORD) as Total,CLCNO,LPROD ')
            ->whereraw("(LPROD='" . $finalesecl . "')")
            ->where([
                ['LSDTE', '>=', $hoy],
                ['LSDTE', '<', $totalF],
            ])
            ->groupBy('LPROD', 'LSDTE', 'CLCNO')
            ->get()->toarray();

        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN', 'IMSPKT')
            ->whereraw("(IPROD='" . $Qa . "')")
            ->get()->toArray();

        $prodcqa = array_column($cond, 'IPROD');
        $pqa = array_column($cond, 'IMBOXQ');
        $typkt = array_column($cond, 'IMSPKT');
        $minba = array_column($cond, 'IMIN');


        $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" . $finaleswrk . "')")
            ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');
        $wk = array_column($WCT, 'RWRKC');
        $valPDp = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" . $finaleskfp . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();

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
            if (count($valfinales) > 0) {
                $total = 0;
                foreach ($valfinales as $reg4) {
                    if ($reg4['MPROD'] == $prod['IPROD']) {
                        $dia = $reg4['MRDTE'];
                        $turno = $reg4['MRCNO'];
                        $total = $reg4['MQTY'] + 0;
                        $valt = substr($turno, 4, 1);
                        $forcastp += ['For' . $dia . $valt => $total];
                        $totalP = $totalP + $total;
                    }
                }
            }
            if (count($MBMS) > 0) {
                foreach ($MBMS as $reg1) {
                    if ($reg1['LPROD'] == $prod['IPROD']) {
                        $dia = $reg1['LSDTE'];
                        $turno = $reg1['CLCNO'];
                        $total = $reg1['TOTAL'] + 0;
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
            $pos = array_search($prod['IPROD'], $prodcqa);
            $padre += ['Qty' => $pqa[$pos] ?? 0];
            $padre += ['typkt' => $typkt[$pos] ?? 'N/A'];
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

    public function Buscar(Request $request)
    {
        $inF1 = array();
        $inF2 = array();
        $tipo = $request->Planeacion;
        $parte = $request->item;
        $dias = 8;
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $CP = '';

        $plan1 = IIM::query()
            ->select('IPROD', 'IREF04')
            ->where([['IPROD ', $request->item], ['ICLAS', 'F1']])
            ->get()->toArray();


        if ($request->Type == 1) {
            $datos = self::CargarforcastF1only($plan1, $fecha, $dias);

            return view('planeacion.planfinal1', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC ?? '', 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar ?? '', 'pagina' => 0, 'tpag' => $total ?? 0]);
        } else {
            $datos = self::CargarforcastF1($plan1, $fecha, $dias);

            return view('planeacion.plancomponente', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC ?? '', 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar ?? '', 'pagina' => 0, 'tpag' => $total ?? 0]);
        }
    }
}
