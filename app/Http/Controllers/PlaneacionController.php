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
use App\Models\Fso;
use App\Models\YK006;
use App\Models\MStructure;
use Carbon\Carbon;
use registros;
use App\Exports\PlanExport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;


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

        $datos = self::CargarforcastF1($plan1, $fecha, $dias);
        $currentPage = 1;
        $perPage = 2;

        $currentElements = array_slice($datos, $perPage * ($currentPage - 1), $perPage);
        $res = new LengthAwarePaginator($currentElements, count($datos), $perPage, $currentPage, ['path' => url('/planeacion/create')]);
        // $res = new Paginator($datos, 2);
        // $res = self::paginate($currentElements, $perPage, $currentPage);
        $currentPage = $request->page;


        $res = new LengthAwarePaginator($currentElements, count($datos), $perPage, $currentPage);

        return view('planeacion.plancomponente', ['res' => $datos , 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
    }
    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    public function export(Request $request)
    {

        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $fechaFin = $request->fechaFin != '' ? Carbon::parse($request->fechaFin)->format('Ymd') : Carbon::now()->format('Ymd');


        return Excel::download(new PlanExport($fecha, $fechaFin), 'Planeacion.xlsx');
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

        $inF1 = array();
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $WC = $request->SeWC;
        $variables = $request->all();

        $keyes = array_keys($variables);
        $data = explode('/', $keyes[1], 2);
        $dias =  $data[1];
        $fecha =  $data[0];

        $hoy = date('Ymd', strtotime($fecha));
        $datas = [];
        $datasql = [];
        $CONT=0;
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
                $fechasql =   date('Ymd',strtotime( $inp[1]));


                if($request->$plans != 0) {

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
                        'K6DDTE' => $fechasql ,
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
            if($CONT==100)
            {

                 $indata = YK006::query()->insert($datas);

                $insql = LOGSUP::query()->insert($datasql);

                $datas=[];
                $CONT=0;
            }
            $CONT=$CONT+1;

        }

            $indata = YK006::query()->insert($datas);
            $indatasql = LOGSUP::query()->insert($datasql);



        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YMP006C";
        $result = odbc_exec($conn, $query);

        $plan = Iim::query()
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
        $datos = self::CargarforcastF1($plan, $fecha, $dias);

        return view('planeacion.plancomponente', ['res' => $datos, 'plantotal' => $plan, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
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
            ->where('MRDTE', '<=',  $totalF)
            ->whereraw("(MPROD='" . $finales  . "')")
            ->get()->toarray();

        $valPDp  = kFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<=', $totalF],
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
                $forcastp = [];
                $padre  += ['parte' => $prod['IPROD']];
                if (count($valfinales) > 0) {
                    foreach ($valfinales  as $reg4) {
                        if ($reg4['MPROD'] == $prod['IPROD']) {
                            $dia = $reg4['MRDTE'];
                            $turno =  $reg4['MRCNO'];
                            $total = $reg4['MQTY'] + 0;
                            $valt = substr($turno, 4, 1);
                            $forcastp  += ['For' . $dia . $valt => $total];
                        }
                    }
                }


                if (count($valPDp) > 0) {
                    foreach ($valPDp  as $reg6) {
                        if ($reg6['FPROD'] == $prod['IPROD']) {
                            $dia = $reg6['FRDTE'];
                            $turno =  $reg6['FPCNO'];
                            $tipo =  $reg6['FTYPE'];
                            $total = $reg6['FQTY'] + 0;
                            $valt = substr($turno, 4, 1);
                            $planpadre += [$tipo . $dia . $valt => $total];
                        }
                    }
                }
                $padre  += $forcastp;
                $padre  +=  $planpadre;

                $inF1 += ['padre' =>  $padre];

                $datossub = self::Cargarforcast($prod['IPROD'], $hoy, $dias,  $forcastp);
                $inF1 += ['hijos' =>  $datossub];
                array_push($totalpa, $inF1);
            }
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
        $cadsubswrk = implode("' OR  RPROD='",  $sub1);
        $Qa = implode("' OR  IPROD='",  $sub1);

        $valPD = kFP::query()
            ->select('FPROD', 'FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->whereraw("(FPROD='" .  $cadsubsPlan . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<=', $totalF],
            ])
            ->get()->toarray();

        $cadsubssh = implode("' OR  SPROD='",  $sub1);
        $valSD = Fso::query()
            ->select('SPROD', 'SDDTE', 'SQREQ', 'SOCNO')
            ->whereraw("(SPROD='" .  $cadsubssh  . "')")
            ->where('SDDTE', '>=', $hoy)
            ->where('SDDTE', '<=', $totalF)
            ->get()->toarray();



        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN')
            ->whereraw("(IPROD='" . $Qa  . "')")
            ->get()->toArray();

        $WCT = Frt::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" .  $cadsubswrk  . "')")
            ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');
        $prowrok = array_column($WCT, 'RWRKC');
        $prodcqa = array_column($cond, 'IPROD');
        $pqa = array_column($cond, 'IMBOXQ');
        $minba = array_column($cond, 'IMIN');
        $sepa = [];

        foreach ($sub1 as $subs) {
            $contF1 = self::contcargarF1($subs);

            if ($contF1 > 1) {
                $F1 = self::cargarF1($subs);
                $padres1 = array_column($F1, 'final');
                $texpadre = implode(',' . ' <br> ', $padres1);
                $cadsubs = implode("' OR  MPROD='",  $padres1);
                $cadsubsL = implode("' OR  LPROD='",  $padres1);
            } else {
                $cadsubs = $prod1;
                $cadsubsL = $prod1;
                $texpadre = $prod1;
            }

            $MBMS = ECL::query()
                ->selectRaw('LSDTE, SUM(LQORD) as Total,CLCNO,LPROD ')
                ->whereraw("(LPROD='" .  $cadsubsL . "')")
                ->where([
                    ['LSDTE', '>=', $hoy],
                    ['LSDTE', '<=', $totalF],
                ])
                ->groupBy('LPROD', 'LSDTE', 'CLCNO')
                ->get()->toarray();
            $RFMA = FMA::query()
                ->selectRaw('MPROD,MRDTE, SUM(MQREQ) as Total')
                ->whereraw("(MPROD='" .  $cadsubs . "')")
                ->where([
                    ['MRDTE', '>=', $hoy],
                    ['MRDTE', '<=', $totalF],
                ])
                ->groupBy('MPROD', 'MRDTE')
                ->get()->toarray();

            $RKMR = KMR::query()
                ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
                ->whereraw("(MPROD='" .  $cadsubs . "')")
                ->where([
                    ['MRDTE', '>=', $hoy],
                    ['MRDTE', '<=', $totalF],
                ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
                ->get()->toarray();

            $forcast = [];


            if (count($RKMR) > 0) {


                foreach ($RKMR as $reg) {
                    $dia =  $reg['MRDTE'];
                    $turno =  $reg['MRCNO'];
                    $total =  $reg['TOTAL'] + 0;
                    $valt = substr($turno, 4, 1);
                    $forcast  += ['kmr' . $dia . $valt => $total];
                }
            }
            if (count($MBMS) > 0) {
                foreach ($MBMS as $reg1) {


                    $dia =  $reg1['LSDTE'];
                    $turno =  $reg1['CLCNO'];
                    $total =  $reg1['TOTAL'] + 0;
                    $valt = substr($turno, 4, 1);
                    $forcast  += ['ecl' . $dia . $valt => $total];
                }
            }

            if (count($RFMA) > 0) {
                foreach ($RFMA  as $reg2) {
                    $dia =  $reg2['MRDTE'];
                    $total =  $reg2['TOTAL'] + 0;
                    $forcast += ['FMA' . $dia . 'D' => $total];
                }
            }


            $numpar = [];
            $numpaplan =  $valDp;
            foreach ($valPD as $reg3) {
                if ($reg3['FPROD'] == $subs) {
                    $dia =  $reg3['FRDTE'];
                    $turno =  $reg3['FPCNO'];
                    $tipo =  $reg3['FTYPE'];
                    $total =  $reg3['FQTY'] + 0;
                    $valt = substr($turno, 4, 1);
                    $numpaplan += [$tipo . $dia . $valt => $total];
                }
            }
            foreach ($valSD as $reg4) {
                if ($reg4['SPROD'] == $subs) {
                    $dia =  $reg4['SDDTE'];
                    $turno =  $reg4['SOCNO'];
                    $total =  $reg4['SQREQ'] + 0;
                    $valt = substr($turno, 4, 1);
                    $numpaplan += ['S' . $dia . $valt => $total];
                }
            }

            $pos = array_search($subs, $prodcqa);
            $poskwr = array_search($subs,   $prowk);

            $numpar += ['sub' => $subs, 'plan' => $numpaplan,  'padres' => $texpadre, 'forcast' => $forcast, 'Qty' => $pqa[$pos], 'minbal' => $minba[$pos], 'wrk' => $prowrok[$poskwr]];
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
