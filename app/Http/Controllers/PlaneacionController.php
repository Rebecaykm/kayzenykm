<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\LWK;
use App\Models\IPB;
use App\Models\KMR;
use App\Models\kFP;
use App\Models\frt;
use App\Models\Iim;
use App\Models\ZCC;
use App\Models\LOGSUP;
use App\Models\Fma;
use App\Models\Ecl;
use App\Models\MBMr;
use App\Models\YMCOM;
use App\Models\Fso;
use App\Models\YK006;
use App\Models\MStructure;
use Carbon\Carbon;
use registros;
use App\Exports\PlanExport;
use App\Exports\PlanFinalExport;
use App\Exports\PlansubExport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;


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

        $dias = $request->NP ?? '*';
        $fecha = $request->Seproject ?? '*';
        $plan = '';
        $TP = 'NO';
        $CP = '';
        $WC = '';
        $WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();
        return view('planeacion.index', ['LWK' => $WCs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function create(Request $request)
    {
        $inF1 = array();
        $inF2 = array();
        $tipo = $request->Planeacion;

        $dias = $request->dias ?? '8';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;

        $CP = $request->SePC;
        $WC = $request->SeWC;
        $plan1 = Iim::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            // ->where('IPROD', 'Not like', '%-SOR%')
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();

        if ($tipo == 2) {
            $padres = array_chunk($plan1, 5);

            $total = count($padres);

            $datos = self::CargarforcastF1($padres[0], $fecha, $dias);

            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='",      $partsrev);
            return view('planeacion.plancomponente', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' =>  $cadepar, 'pagina' => 0, 'tpag' => $total]);
        } else {
            $total = 0;
            $datos = self::CargarforcastF1only($plan1, $fecha, $dias);

            $partsrev = array_column($plan1, 'IPROD');
            $cadepar = implode("' OR  IPROD='",      $partsrev);
            return view('planeacion.planfinal1', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' =>  $cadepar, 'pagina' => 0, 'tpag' => $total]);
        }
    }


    public function siguiente(Request $request)
    {
        $inF1 = array();
        $inF2 = array();
        $tipo = $request->SeProject;
        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $WC = $request->SeWC;
        $plan1 = Iim::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('IPROD', 'Not like', '%-SOR%')
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();
        $padres = array_chunk($plan1, 10);
        $partsrev = array_column($plan1, 'IPROD');

        $total = count($padres) - 1;
        $datos = self::CargarforcastF1($padres[$request->paginate], $fecha, $dias);


        $cadepar = $request->nextp . "and IPROD!=" . implode("' OR  IPROD='",      $partsrev);

        return view('planeacion.plancomponente', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $request->fecha, 'dias' => $request->dias, 'partesne' => $cadepar, 'pagina' => $request->paginate, 'tpag' => $total]);
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
        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $fechaFin = $request->fechaFin != '' ? Carbon::parse($request->fechaFin)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;

        return Excel::download(new PlanFinalExport($fecha,  $dias, $TP), 'PartesfinalesP' . $TP . '.xlsx');
    }
    public function exportsubcomponentes(Request $request)
    {
        $inF1 = array();
        $inF2 = array();
        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $fechaFin = $request->fechaFin != '' ? Carbon::parse($request->fechaFin)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;

        return Excel::download(new PlansubExport($fecha,  $dias, $TP), 'PartessubcomponentesP' . $TP . '.xlsx');
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
        $dias =  $data[1];
        $fecha =  $data[0];

        $hoy = date('Ymd', strtotime($fecha));
        $datas = [];
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
                $fechasql =   date('Ymd', strtotime($inp[1]));


                if ($request->$plans != 0) {

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
            }
            if ($CONT == 5) {

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

        $plan1 = Iim::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('IPROD', 'Not like', '%-SOR%')
            ->whereraw("(IPROD='" . $request->nextp . "')")
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();

        $datos = self::CargarforcastF1only($plan1, $fecha, $dias);
        $partsrev = array_column($plan1, 'IPROD');
        $cadepar = $request->nextp . "and IPROD!=" . implode("' OR  IPROD='",      $partsrev);
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
        $dias =  $data[1];
        $fecha =  $data[0];

        $hoy = date('Ymd', strtotime($fecha));
        $datas = [];
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
                $fechasql =   date('Ymd', strtotime($inp[1]));


                if ($request->$plans != 0) {

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
            }
            if ($CONT == 10) {

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



        // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        // $query = "CALL LX834OU02.YMP006C";
        // $result = odbc_exec($conn, $query);

        $plan1 = Iim::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('IPROD', 'Not like', '%-SOR%')
            ->whereraw("(IPROD='" . $request->nextp . "')")
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();


        $padres = array_chunk($plan1, 10);
        $total = count($padres);
        $datos = self::CargarforcastF1($padres[$request->paginate], $fecha, $dias);
        $partsrev = array_column($plan1, 'IPROD');
        $cadepar = $request->nextp . "and IPROD!=" . implode("' OR  IPROD='",      $partsrev);
        return view('planeacion.plancomponente', ['res' => $datos, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias, 'partesne' => $cadepar, 'pagina' => $request->paginate, 'tpag' => $total]);
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
    function info($producto)
    {
        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC')
            ->where('IPROD', '=', $producto)
            ->first()->toArray();
        return $cond;
    }


    function contard($producto, $fecha, $turno)
    {

        $cond = kmr::query()
            ->select('MPROD', 'MQTY', 'MRDTE', 'MRCNO')
            ->where('MRDTE', '=', $fecha)
            ->where('MPROD', '=', $producto)
            ->where('MRCNO', 'like', $turno)
            ->count();
        return $cond;
    }


    function contar($producto, $fecha, $fechafin)
    {
        $WCs = kmr::query()
            ->select('MPROD', 'MQTY', 'MRDTE', 'MRCNO')
            ->where('MRDTE', '>=', $fecha)
            ->where('MRDTE', '<=', $fechafin)
            ->where('MPROD', '=', $producto)
            ->count();
        return $WCs;
    }
    function padre($pro)
    {

        $MBMS = MBMr::query()
            ->select('BPROD', 'BCLAS', 'BCHLD', 'BCLAC', 'BDDIS')
            ->where('BCHLD', '=', $pro)
            ->where('BDDIS', '>=', '9900000')
            ->get();
        return $MBMS;
    }
    function padrecon($pro)
    {

        $MBMS = MBMr::query()
            ->select('BPROD', 'BCLAS', 'BCHLD', 'BCLAC', 'BDDIS')
            ->where('BCHLD', '=', $pro)
            ->where('BDDIS', '=', '99999999')
            ->count();
        return $MBMS;
    }
    function F1($pro)
    {

        $MBMS = MBMr::query()
            ->select('BPROD', 'BCLAS', 'BCHLD', 'BCLAC', 'BDDIS')
            ->where('BCHLD', '=', $pro)
            ->where('BCLAS', '=', 'F1')
            ->get();

        return $MBMS;
    }
    function contarF1($pro)
    {
        $MBMS = MBMr::query()
            ->select('BPROD')
            ->where('BCHLD', '=', $pro)
            ->where('BCLAS', '=', 'F1')
            ->where('BDDIS', '>=', '99999997')
            ->count();
        return $MBMS;
    }
    //---------------------------------------Buscar estructuras ------------------------------------------------------
    function contcargar($prod)
    {
        $res = MStructure::query()
            ->select('Final')
            ->where('Final', $prod)
            ->where('clase', '!=', '01')
            ->count();
        return $res;
    }
    function cargar($prod)
    {
        $res = MStructure::query()
            ->select('Final', 'Componente', 'Activo', 'Clase')
            ->where('Final', $prod)
            ->where([
                ['clase', '!=', '01'],
                ['clase', '!=', 'F1'],
                ['Activo', '1'],
            ])
            ->get()->toArray();

        return $res;
    }
    function cargarestructura($prod)
    {
        $res = MStructure::query()
            ->select('Final', 'Componente', 'Activo')
            ->where('Final', $prod)
            ->where([
                ['clase', '!=', '01'],
            ])
            ->get();
        return $res;
    }

    function cargarF1($prod)
    {
        $res = MStructure::query()
            ->select('final')
            ->where('componente', $prod)
            ->distinct('final')
            ->get()->toArray();
        return $res;
    }
    function contcargarF1($prod)
    {
        $res = MStructure::query()
            ->select('Final', 'componente')
            ->where('componente', $prod)
            ->count();
        return $res;
    }
    function CargarforcastF1($prods, $hoy, $dias)
    {
        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $finaArra = array_column($prods, 'IPROD');
        $finales = implode("' OR  MPROD='",   $finaArra);
        $finaleskfp = implode("' OR  FPROD='",   $finaArra);
        $valfinales = kmr::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $hoy)
            ->where('MRDTE', '<',  $totalF)
            ->where('MTYPE', '=', 'F')
            ->whereraw("(MPROD='" . $finales  . "')")
            ->get()->toarray();


        $valPDp  = kFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();

        foreach ($prods as $prod) {
            $contsub = self::contcargar($prod['IPROD']);
            if ($contsub != 0) {
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
                $padre  += ['parte' => $prod['IPROD']];
                if (count($valfinales) > 0) {
                    $total = 0;
                    foreach ($valfinales  as $reg4) {
                        if ($reg4['MPROD'] == $prod['IPROD']) {
                            $dia = $reg4['MRDTE'];
                            $turno =  $reg4['MRCNO'];
                            $total = $reg4['MQTY'] + 0;
                            $valt = substr($turno, 4, 1);
                            $forcastp  += ['For' . $dia . $valt => $total];
                            $totalP = $totalP + $total;
                        }
                    }
                }

                $padre  += ['total' => $totalP];

                if (count($valPDp) > 0) {
                    $total = 0;
                    foreach ($valPDp  as $reg6) {
                        if ($reg6['FPROD'] == $prod['IPROD']) {
                            $dia = $reg6['FRDTE'];
                            $turno =  $reg6['FPCNO'];
                            $tipo =  $reg6['FTYPE'];
                            $total = $reg6['FQTY'] + 0;
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
                $padre  += ['tPlan' => $tPlan];
                $padre  += ['tfirme' => $tfirme];

                $padre  += $forcastp;
                $padre  +=  $planpadre;
                // dd( $padre);
                $inF1 += ['padre' =>  $padre];
                $datossub = self::Cargarforcast($prod['IPROD'], $hoy, $dias,  $forcastp);
                $inF1 += ['hijos' =>  $datossub];
                array_push($totalpa, $inF1);
            }
        }
        return   $totalpa;
    }
    function CargarforcastF1only($prods, $hoy, $dias)
    {
        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $finaArra = array_column($prods, 'IPROD');
        $finales = implode("' OR  MPROD='",   $finaArra);
        $finalesecl = implode("' OR  LPROD='",   $finaArra);
        $finaleswrk = implode("' OR  RPROD='",   $finaArra);
        $Qa = implode("' OR  IPROD='",   $finaArra);
        $finaleskfp = implode("' OR  FPROD='",   $finaArra);
        $valfinales = kmr::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $hoy)
            ->where('MRDTE', '<',  $totalF)
            ->where('MTYPE', '=', 'F')
            ->whereraw("(MPROD='" . $finales  . "')")
            ->get()->toarray();

        $MBMS = ECL::query()
            ->selectRaw('LSDTE, SUM(LQORD) as Total,CLCNO,LPROD ')
            ->whereraw("(LPROD='" .  $finalesecl . "')")
            ->where([
                ['LSDTE', '>=', $hoy],
                ['LSDTE', '<', $totalF],
            ])
            ->groupBy('LPROD', 'LSDTE', 'CLCNO')
            ->get()->toarray();

        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN')
            ->whereraw("(IPROD='" . $Qa  . "')")
            ->get()->toArray();

        $prodcqa = array_column($cond, 'IPROD');
        $pqa = array_column($cond, 'IMBOXQ');
        $minba = array_column($cond, 'IMIN');



        $WCT = Frt::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" . $finaleswrk . "')")
            ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');
        $wk = array_column($WCT, 'RWRKC');
        $valPDp  = kFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();

        foreach ($prods as $prod) {
            $contsub = self::contcargar($prod['IPROD']);

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
            $padre  += ['parte' => $prod['IPROD']];
            if (count($valfinales) > 0) {
                $total = 0;
                foreach ($valfinales  as $reg4) {
                    if ($reg4['MPROD'] == $prod['IPROD']) {
                        $dia = $reg4['MRDTE'];
                        $turno =  $reg4['MRCNO'];
                        $total = $reg4['MQTY'] + 0;
                        $valt = substr($turno, 4, 1);
                        $forcastp  += ['For' . $dia . $valt => $total];
                        $totalP = $totalP + $total;
                    }
                }
            }
            if (count($MBMS) > 0) {
                foreach ($MBMS as $reg1) {
                    if ($reg1['LPROD'] == $prod['IPROD']) {
                        $dia =  $reg1['LSDTE'];
                        $turno =  $reg1['CLCNO'];
                        $total =  $reg1['TOTAL'] + 0;
                        $valt = substr($turno, 4, 1);
                        $forcastp += ['ecl' . $dia . $valt => $total];
                    }
                }
            }

            $padre  += ['total' => $totalP];

            if (count($valPDp) > 0) {
                $total = 0;
                foreach ($valPDp  as $reg6) {
                    if ($reg6['FPROD'] == $prod['IPROD']) {
                        $dia = $reg6['FRDTE'];
                        $turno =  $reg6['FPCNO'];
                        $tipo =  $reg6['FTYPE'];
                        $total = $reg6['FQTY'] + 0;
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
            $pos = array_search($prod['IPROD'], $prodcqa);
            $padre += ['Qty' => $pqa[$pos] ?? 0];
            $padre  += ['tPlan' => $tPlan];
            $padre  += ['tfirme' => $tfirme];
            $poskwr = array_search($prod['IPROD'],   $prowk);
            $padre += ['WRC' =>  $wk[$poskwr] ?? '202020020202020'];


            $padre  += $forcastp;
            $padre  +=  $planpadre;
            // dd( $padre);
            $inF1 += ['padre' =>  $padre];

            array_push($totalpa, $inF1);
        }

        return   $totalpa;
    }

    function Cargarforcast($prod1, $hoy, $dias, $valDp)
    {

        $Sub = self::cargar($prod1);
        $inF1 = array();
        $total = array();
        $i = 0;
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $sub1 = array_column($Sub, 'Componente');
        $cadsubsPlan = implode("' OR  FPROD='",  $sub1);
        $child = implode("' OR  MCCPRO='",  $sub1);
        $cadsubKMR = implode("' OR  MPROD='",  $sub1);
        $cadsubswrk = implode("' OR  RPROD='",  $sub1);
        $Qa = implode("' OR  IPROD='",  $sub1);
        $valPD = kFP::query()
            ->select('FPROD', 'FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->whereraw("(FPROD='" .  $cadsubsPlan . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();

        $VALRKMR = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
            ->whereraw("(MPROD='" .   $cadsubKMR . "')")
            ->where([
                ['MRDTE', '>=', $hoy],
                ['MRDTE', '<', $totalF],
            ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
            ->get()->toarray();
        $KMRPARENT = YMCOM::query()
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
            ->whereraw("(MCCPRO='" .   $child  . "') AND (MCFCLS='M2' or  MCFCLS='M3' or  MCFCLS='M4' or  MCFCLS='F1')")
            ->get()->toarray();
            $kmrmccprod = array_column($KMRPARENT , 'MCCPRO');
            $kmrmcfprod= array_column($KMRPARENT , 'MCFPRO');
            $KMRMCFCLS = array_column($KMRPARENT , 'MCFCLS');
        $cadsubssh = implode("' OR  SPROD='",  $sub1);
        $valSD = Fso::query()
            ->select('SPROD', 'SDDTE', 'SQREQ', 'SOCNO')
            ->whereraw("(SPROD='" .  $cadsubssh  . "')")
            ->where('SDDTE', '>=', $hoy)
            ->where('SDDTE', '<', $totalF)
            ->get()->toarray();


        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN')
            ->whereraw("(IPROD='" . $Qa  . "')")
            ->get()->toArray();

        $WCT = Frt::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" .  $cadsubswrk  . "')")
            ->get()->toarray();


        $RFMA = FMA::query()
            ->selectRaw('MPROD,MRDTE, SUM(MQREQ) as Total')
            ->whereraw("(MPROD='" .  $cadsubKMR  . "')")
            ->where([
                ['MRDTE', '>=', $hoy],
                ['MRDTE', '<', $totalF],
            ])
            ->groupBy('MPROD', 'MRDTE')
            ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');
        $prowrok = array_column($WCT, 'RWRKC');

        $prodcqa = array_column($cond, 'IPROD');
        $pqa = array_column($cond, 'IMBOXQ');
        $minba = array_column($cond, 'IMIN');
        $sepa = [];

        foreach ($sub1 as $subs) {
            $padreskmr = [];
            $finaleskmr = [];
            while (($key2 = array_search($subs,  $kmrmccprod)) != false) {

                if ( $KMRMCFCLS[$key2]== 'F1' && $kmrmcfprod[$key2]!=$subs) {
                    array_push($finaleskmr,$kmrmcfprod[$key2]);
                }else
                {
                    array_push($padreskmr,$kmrmccprod[$key2]);
                }
                unset($kmrmccprod[$key2]);
                unset($KMRMCFCLS[$key2]);
                unset( $kmrmcfprod[$key2]);
            }

            $contpadres = count($padreskmr);
            if ($contpadres  >= 1) {

                $texpadre = implode(',' . ' <br> ',  $padreskmr);
                $cadsubs = implode("' OR  MPROD='",   $padreskmr);
            } else {
                $cadsubs = '00';
                // $cadsubsL = $$padreskmr[0];
                $texpadre = '';
            }
            $contF1 = count($finaleskmr);
            if ($contF1 >= 1) {
                // $F1 = self::cargarF1($subs);
                // $padres1 = array_column($KMRPARENT1, 'MCFPRO');

                $texfinal = implode(',' . ' <br> ',    $finaleskmr);
                $cadfinal = implode("' OR  MPROD='",     $finaleskmr);
                // $cadsubsL = implode("' OR  LPROD='", $padreskmr );
            } else {
                $texfinal = $finaleskmr[0]??'';
                // $cadsubsL = $$padreskmr[0];
                $cadfinal = $finaleskmr[0]??'';
            }
            $RKMR = KMR::query()
                ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
                ->whereraw("(MPROD='" .  $cadsubs . "')")
                ->where([
                    ['MRDTE', '>=', $hoy],
                    ['MRDTE', '<', $totalF],
                ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
                ->get()->toarray();
            $RKMRfinal = KMR::query()
                ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
                ->whereraw("(MPROD='" .    $cadfinal . "')")
                ->where([
                    ['MRDTE', '>=', $hoy],
                    ['MRDTE', '<', $totalF],
                ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
                ->get()->toarray();




            $forcast = [];
            $Tshop = 0;
            $Tplan = 0;
            $Tfirme = 0;
            if (count($RKMR) > 0) {

                $total = 0;
                foreach ($RKMRfinal as $reg) {
                    $dia =  $reg['MRDTE'];
                    $turno =  $reg['MRCNO'];
                    $total =  $reg['TOTAL'] + 0;
                    $valt = substr($turno, 4, 1);
                    $forcast  += ['kmr' . $dia . $valt => $total];
                }
            }
            // if (count($MBMS) > 0) {
            //     $total = 0;
            //     foreach ($MBMS as $reg1) {
            //         $dia =  $reg1['LSDTE'];
            //         $turno =  $reg1['CLCNO'];
            //         $total =  $reg1['TOTAL'] + 0;
            //         $valt = substr($turno, 4, 1);
            //         $forcast  += ['ecl' . $dia . $valt => $total];
            //     }
            // }

            if (count($RFMA) > 0) {
                $total = 0;
                foreach ($RFMA  as $reg2) {
                    $dia =  $reg2['MRDTE'];
                    $total =  $reg2['TOTAL'] + 0;
                    $forcast += ['FMA' . $dia . 'D' => $total];
                }
            }


            $numpar = [];
            $numpaplan =  $valDp;
            $total = 0;
            foreach ($valPD as $reg3) {
                if ($reg3['FPROD'] == $subs) {
                    $dia =  $reg3['FRDTE'];
                    $turno =  $reg3['FPCNO'];
                    $tipo =  $reg3['FTYPE'];
                    $total =  $reg3['FQTY'] + 0;
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
                    $dia =  $reg4['SDDTE'];
                    $turno =  $reg4['SOCNO'];
                    $total =  $reg4['SQREQ'] + 0;
                    $valt = substr($turno, 4, 1);
                    $numpaplan += ['S' . $dia . $valt => $total];
                    $Tshop =   $Tshop + $total;
                }
            }
            $total = 0;
            $Tshopkmr = 0;
            foreach ($VALRKMR as $reg10) {
                if ($reg10['MPROD'] == $subs) {
                    $dia =  $reg10['MRDTE'];
                    $turno =  $reg10['MRCNO'];
                    $total =  $reg10['TOTAL'] + 0;
                    $valt = substr($turno, 4, 1);
                    $numpaplan += ['KMRS' . $dia . $valt => $total];
                    $Tshopkmr =   $Tshopkmr + $total;
                }
            }


            $pos = array_search($subs, $prodcqa);
            $poskwr = array_search($subs,   $prowk);
            $numpar += ['sub' => $subs, 'plan' => $numpaplan,  'padres' =>$texfinal, 'forcast' => $forcast, 'Qty' => $pqa[$pos] ?? 0, 'minbal' => $minba[$pos] ?? 0, 'wrk' => $prowrok[$poskwr] ?? 0, 'Tshop' => $Tshop, 'Tplan' => $Tplan, 'Tfirme' => $Tfirme, 'KMRpadres'  =>  $texpadre, 'Totalpadres' => $Tshopkmr];
            $sepa += [$subs => $numpar];
        }
        return    $sepa;
    }



    // ---------------------------------guardar estructuras de BOM----------------------------------------------
    function guardar($prod, $sub, $clase)
    {
        $res = self::buscar($prod, $sub);
        if ($res == 0) {
            $data = MStructure::create([
                'final' => $prod,
                'componente' => $sub,
                'clase' => $clase,
                'Activo' => '1',
            ]);
        }
    }

    function buscar($prod, $sub)
    {
        $data = MStructure::query()
            ->select('Final', 'Componente')
            ->where('Final', '=', $prod)
            ->where('Componente', '=', $sub)
            ->count();

        return $data;
    }
    function buscarF1($prod)
    {
        $a = array(array());
        $i = count($a);
        $hijo = self::Hijo($prod);
        foreach ($hijo as $hijos) {
            $a[$i][0] = $hijos->BCHLD;
            $a[$i][1] = $hijos->BCLAC;
            $Chijo = self::Conthijo($hijos->BCHLD);
            if ($Chijo != 0) {
                $b = self::buscarF1($hijos->BCHLD);
                $i = count($a);
                foreach ($b as $bs) {
                    $j = 0;
                    foreach ($bs as $valor) {
                        $a[$i][$j] = $valor;
                        $j++;
                    }
                    $i++;
                }
            }
            $i++;
        }
        return $a;
    }
    function Conthijo($prod)
    {
        $ContBMS = MBMr::query()
            ->select('BPROD', 'BCLAS', 'BCHLD', 'BCLAC', 'BDDIS', 'IMPLC')
            ->join('LX834F01.IIM', 'LX834F01.IIM.IPROD', '=', 'LX834F01.MBM.BPROD')
            ->where('BPROD', '=', $prod)
            ->where('IMPLC', '!=', 'OBSOLETE')
            ->where(function ($query) {
                $query->where('BCLAC ', 'M2')
                    ->orwhere('BCLAC ', 'M3')
                    ->orwhere('BCLAC ', '01')
                    ->orwhere('BCLAC ', 'M4');
            })
            ->count();
        return $ContBMS;
    }
    function Hijo($prod)
    {
        $MBMS = MBMr::query()
            ->select('BPROD', 'BCLAS', 'BCHLD', 'BCLAC', 'BDDIS')
            ->where('BPROD', '=', $prod)
            ->where(function ($query) {
                $query->where('BCLAC ', 'M2')
                    ->orwhere('BCLAC ', 'M3')
                    ->orwhere('BCLAC ', '01')
                    ->orwhere('BCLAC ', 'M4');
            })
            ->orderby('BCHLD')
            ->get();
        return $MBMS;
    }
}
