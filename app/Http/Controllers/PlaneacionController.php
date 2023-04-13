<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $plan = Iim::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('IPROD', 'Not like', '%-SOR%')
            ->where('ICLAS ', 'F1')
            ->distinct('IPROD')
            ->get();

        $datos = self::CargarforcastF1($plan, $fecha, $dias);

        return view('planeacion.plancomponente', ['plan' => $datos, 'plantotal' => $plan, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
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

        foreach ($keyes as $plans) {
            $inp = explode('/', $plans, 3);
            if (count($inp) >= 3) {
                $namenA = strtr($inp[0], '_', ' ');
                $turno = $inp[2];
                $load = date('Ymd', strtotime('now'));
                $hora = date('His', time());
                $fefin = date('Ymd', strtotime($fecha . '+' . $dias - 1 . ' day'));
                $WCT = Frt::query()
                    ->select('RWRKC', 'RPROD')
                    ->where('RPROD', $namenA)
                    ->value('RWRKC');
                if ($request->$plans != 0) {
                    $data = YK006::query()->insert([
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
                        'K6FIL2' => '',
                    ]);
                    $data = LOGSUP::query()->insert([
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
                        'K6FIL2' => '',
                    ]);
                }

                $hoy = date('Ymd', strtotime($hoy . '+1 day'));
            }
        }
        $plan = Iim::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->where('IPROD', 'Not like', '%-SOR%')
            ->distinct('IPROD')
            ->simplePaginate(10)->withQueryString();
        foreach ($plan as $WCss) {
            $datos = self::CargarforcastF1($WCss->IPROD, $fecha, $dias);
            $inF1 += [$WCss->IPROD =>  $datos];
        }
        dd('slmd');
        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YMP006C";
        $result = odbc_exec($conn, $query);
        return view('planeacion.plancomponente', ['plan' => $inF1, 'plantotal' => $plan, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
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
        $total = array();
        foreach ($prods as $prod) {
            $contsub = self::contcargar($prod->IPROD);
            if ($contsub != 0) {
                $inF1 = array();
                $padre = [];
                $dia = $hoy;
                $connt = 1;
                $i = 0;

                $valDp = self::Forecasttotal($prod->IPROD, $dia, '%D%', $dias);
                if (count($valDp) != 0) {
                    $MdateD = array_column($valDp, 'MRDTE');
                    $Mqty = array_column($valDp, 'MQTY');
                    $mturno = array_column($valDp, 'MRCNO');
                }
                $valPDp = self::planTotal($prod->IPROD, $dia, '%D%', $dias);
                if (count($valPDp) != 0) {
                    $PdateD = array_column($valPDp, 'FRDTE');
                    $PqtyD = array_column($valPDp, 'FQTY');
                    $PturD = array_column($valPDp, 'FPCNO');
                    $PtypeD = array_column($valPDp, 'FTYPE');
                }
                $valSDp = self::ShopOTotal($prod->IPROD, $dia, '%D%', $dias);
                if (count($valSDp) != 0) {
                    $SdateD = array_column($valSDp, 'SDDTE');
                    $SqtyD = array_column($valSDp, 'SQREQ');
                    $SturD = array_column($valSDp, 'SOCNO');
                }

                $padre += ['parte' => $prod->IPROD];
                while ($connt <= $dias) {
                    $i++;
                    if (count($valDp) != 0) {

                        if (is_int(array_search($dia, $MdateD))) {
                            $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                            $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
                            $padre += ['F' . $dia . $valt => $val1];
                            unset($MdateD[array_search($dia, $MdateD)]);
                            if (is_int(array_search($dia, $MdateD))) {
                                $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                                $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
                                $padre += ['F' . $dia . $valt => $val1];
                                // $inF1 += ['F' . $dia . 'D' => $val1];
                            }
                        }
                    }
                    if (count($valPDp) != 0) {
                        if (is_int(array_search($dia, $PdateD))) {
                            $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                            $valtp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                            $padre += ['P' . $dia .  $valtp => $val5];
                            unset($PdateD[array_search($dia, $PdateD)]);
                            if (is_int(array_search($dia, $PdateD))) {
                                $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                                $valtp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                                $padre += [$PtypeD[array_search($dia, $PdateD)] . $dia .  $valtp => $val5];
                            }
                        }
                    }
                    if (count($valSDp) != 0) {
                        $val7D = 0;
                        $val7N = 0;
                        while (is_int(array_search($dia, $SdateD))) {
                            $valtp = substr($SturD[array_search($dia, $SdateD)], 4, 1);
                            if ($valtp == 'D') {
                                $val7D = $SqtyD[array_search($dia, $SdateD)] + $val7D;
                            } else {
                                $val7N = $SqtyD[array_search($dia, $SdateD)] + $val7N;
                            }

                            unset($SdateD[array_search($dia,  $SdateD)]);
                        }
                        if ($val7N != 0) {
                            $padre += ['S' . $dia . 'N'  => $val7N];
                        }
                        if ($val7D != 0) {
                            $padre += ['S' . $dia . 'D'  => $val7D];
                        }
                    }
                    $dia = date('Ymd', strtotime($dia . '+1 day'));
                    $connt++;
                }
                $inF1 += ['padre' => $padre];

                $datossub = self::Cargarforcast($prod->IPROD, $hoy, $dias, $valDp);
                $inF1 += ['hijos' =>  $datossub];


                array_push($total, $inF1);
            }
        }


        return $total;
    }

    function Cargarforcast($prod1, $hoy, $dias, $Fpadre)
    {
        $Sub = self::cargar($prod1);
        $inF1 = array();
        $total = array();
        $i = 0;
        foreach ($Sub as $subs) {
            $prod = $subs['Componente'];
            $inF1 = [
                'sub' => $prod,
            ];
            $dia = $hoy;
            $connt = 1;
            $valRD = self::RequeTotalh($prod1, $prod, $dia, '%D%', $dias);
            $inF1 += $valRD;
            array_push($total, $inF1);
        }

        return $total;
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
    function Projecto($proj)
    {
        $PCs = ZCC::query()
            ->select('CCDESC')
            ->where([['CCID', '=', 'CC'], ['CCTABL', '=', 'SIRF4'], ['CCCODE', '=', $proj]])
            ->first();

        return $PCs;
    }

    // -----------------------------------------------------------------------------------

    function Forecast($producto, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $plan = kmr::query()
            ->select('MQTY', 'MRDTE', 'MRCNO')
            ->where('MRDTE', '>=', $fecha)
            ->where('MRDTE', '<=', $totalF)
            ->whereraw('(' . $producto . ')')
            // ->where('MRCNO', 'like', $turno)
            // ->groupby('MRDTE','MRCNO')
            ->get()->toarray();
        // ->sum('MQTY');
        return $plan;
    }
    function ForecastTOTAL($producto, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));


        $plan = kmr::query()
            ->select('MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $fecha)
            ->where('MRDTE', '<=', $totalF)
            ->where('MPROD', '=', $producto)
            // ->where('MRCNO', 'like', $turno)
            ->get()->toarray();

        return $plan;
    }
    function Forecasttodos($producto, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $plan = kmr::query()
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $fecha)
            ->where('MRDTE', '<=', $totalF)
            ->whereraw('(' . $producto . ')')
            // ->where('MRCNO', 'like', $turno)
            ->get()->toarray();

        return $plan;
    }

    function plan($pro, $fecha, $turno)
    {
        $kfps = kFP::query()
            ->select('FQTY')
            ->where('FPROD', '=', $pro)
            ->where('FPCNO', 'like', $turno)
            ->where('FRDTE', '=', $fecha)
            ->where('FTYPE', '=', 'P')
            ->sum('FQTY');
        return $kfps;
    }
    function planTotal($pro, $fecha, $turno, $dias)
    {

        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $kfps = kFP::query()
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->where([
                ['FPROD', '=', $pro],
                // ['FPCNO', 'like',  $turno],
                ['FRDTE', '>=', $fecha],
                ['FRDTE', '<=', $totalF],
                // ['FTYPE', '=', 'P']
            ])
            ->get()->toarray();

        return $kfps;
    }

    function FirmeTotal($pro, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $kfps = kFP::query()
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->where('FPROD', '=', $pro)
            // ->where('FPCNO', 'like', $turno)
            ->where('FRDTE', '>=', $fecha)
            ->where('FRDTE', '<=', $totalF)
            ->where('FTYPE', '=', 'F')
            ->get()->toarray();

        return $kfps;
    }

    function ShopO($pro, $fecha, $turno)
    {
        $Fsos = Fso::query()
            ->select('SQREQ')
            ->where('SPROD', '=', $pro)
            ->where('SOCNO', 'like', $turno)
            ->where('SDDTE', '=', $fecha)
            ->sum('SQREQ');

        return $Fsos;
    }

    function ShopOTotal($pro, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $Fsos = Fso::query()
            ->select('SDDTE', 'SQREQ', 'SOCNO')
            ->where('SPROD', '=', $pro)
            // ->where('SOCNO', 'like', $turno)
            ->where('SDDTE', '>=', $fecha)
            ->where('SDDTE', '<=', $totalF)
            ->get()->toarray();

        return $Fsos;
    }
    function contarfirme($pro, $fecha, $fechafin)
    {
        $kfps = kFP::query()
            ->select('FPROD', 'FQTY', 'FTYPE', 'FRDTE', 'FPCNO')
            ->where('FPROD', '=', $pro)
            ->where('FRDTE', '>=', $fecha)
            ->where('FRDTE', '<=', $fechafin)
            ->where('FTYPE', '=', 'F')
            ->count();
        return $kfps;
    }
    function contarplan($pro, $fecha, $fechafin)
    {
        $kfps = kFP::query()
            ->select('FPROD', 'FQTY', 'FTYPE', 'FRDTE', 'FPCNO')
            ->where('FPROD', '=', $pro)
            ->where('FRDTE', '>=', $fecha)
            ->where('FRDTE', '<=', $fechafin)
            ->where('FTYPE', '=', 'P')
            ->count();
        return $kfps;
    }

    function contarShopO($pro, $fecha, $fechafin)
    {
        $Fsos = Fso::query()
            ->select('SQREQ')
            ->where('SPROD', '=', $pro)
            ->where('SDDTE', '>=', $fecha)
            ->where('SDDTE', '<=', $fechafin)
            ->count();
        return $Fsos;
    }

    function requerimiento($Lproducto, $Mproducto, $fecha, $turno)
    {
        $MBMS = 0;
        $RFMA = 0;
        $RKM = 0;
        $tot = [];

        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "SELECT TECL,TF,TKMR,T.LPROD as prod FROM (select sum(LQORD)AS TECL, LSDTE,LPROD
            from LX834F01.ECL
            where  (" . $Lproducto . "  ) and LSDTE = '" . $fecha . "'
            AND CLCNO LIKE '%D%'
            GROUP BY LSDTE,LPROD ) T
            FULL OUTER JOIN (
            select sum(MQREQ) AS TF,MRDTE AS fecha,mPROD
            from LX834F01.FMA where  (" . $Mproducto . ")
             and mRDTE ='" . $fecha . "'
             GROUP BY  MRDTE,mPROD
             ) D  ON T.LPROD=D.mPROD
            full OUTER JOIN (SELECT sum(MQTY) AS TKMR,MRDTE AS fecha,MPROD
            FROM LX834F01.KMR
            WHERE MRDTE='" . $fecha . "'
            AND  (" . $Mproducto . ")
            AND MRCNO LIKE '" . $turno . "'
            GROUP BY  MRDTE,MPROD) K ON K.MPROD=T.LPROD";
        $result = odbc_exec($conn, $query);
        $sumre1 =  odbc_result($result, 'TECL') + odbc_result($result, 'TF') + odbc_result($result, 'TKMR') + 0;
        $tot += ['D' => $sumre1];
        // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "SELECT TECL,TKMR,T.LPROD as prod FROM (select sum(LQORD)AS TECL, LSDTE,LPROD
            from LX834F01.ECL
            where  (" . $Lproducto . "  )
            and LSDTE = '" . $fecha . "'
            AND CLCNO LIKE '%N%'
            GROUP BY LSDTE,LPROD ) T
            full OUTER JOIN (SELECT sum(MQTY) AS TKMR,MRDTE AS fecha,MPROD
            FROM LX834F01.KMR
            WHERE MRDTE='" . $fecha . "'
            AND  (" . $Mproducto . ")
            AND MRCNO LIKE '" . $turno . "'
            GROUP BY  MRDTE,MPROD) K ON K.MPROD=T.LPROD";
        $result = odbc_exec($conn, $query);
        $sumre2 =  odbc_result($result, 'TECL') + odbc_result($result, 'TKMR') + 0;
        $tot += ['N' => $sumre2];
        return  $tot;
    }
    function RequeTotal($producto, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $dia = $fecha;
        $connt = 0;
        $valTotal  = [];
        $MBMS = ECL::query()
            ->selectRaw('LSDTE, SUM(LQORD) as Total')
            ->where([
                ['LPROD', '=', $producto],
                ['LSDTE', '>=', $fecha],
                ['LSDTE', '<=', $totalF],
                ['CLCNO', 'Like', $turno]
            ])
            ->groupBy('LSDTE')
            ->get()->toarray();
        if ($turno == '%D%') {
            $RFMA = FMA::query()
                ->selectRaw('MRDTE, SUM(MQREQ) as Total')
                ->where([
                    ['MPROD', '=', $producto],
                    ['MRDTE', '>=', $fecha],
                    ['MRDTE', '<', $totalF],
                ])
                ->groupBy('MRDTE')
                ->get()->toarray();
        }

        $RKMR = KMR::query()
            ->selectRaw('SUM(MQTY) as Total, MRDTE')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '>=', $fecha],
                ['MRDTE', '<', $totalF],
                ['MRCNO', 'Like', $turno]
            ])
            ->groupBy('MRDTE')
            ->get()->toarray();

        while ($connt < $dias) {
            $total = 0;
            $totalf = 0;
            $totalk = 0;
            $DMBMS = array_column($MBMS, 'LSDTE');
            $VMBMS = array_column($MBMS, 'TOTAL');

            if (is_int(array_search($dia, $DMBMS))) {

                $total =  $VMBMS[array_search($dia, $DMBMS)];
            }

            if ($turno == '%D%') {

                $DFMA = array_column($RFMA, 'MRDTE');
                $VFMA = array_column($RFMA, 'TOTAL');
                if (is_int(array_search($dia, $DFMA))) {
                    $totalf = $VFMA[array_search($dia, $DFMA)];
                }
            }
            $DKMR = array_column($RKMR, 'MRDTE');
            $VKMR = array_column($RKMR, 'TOTAL');
            if (is_int(array_search($dia, $DKMR))) {
                $totalk = $VKMR[array_search($dia, $DKMR)];
            }
            $tt = $total + $totalf + $totalk;
            $valTotal += [$dia => $tt];
            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
        }
        return $valTotal;
    }

    function RequeTotalh($producto, $final, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $dia = $fecha;
        $connt = 0;
        $valTotal  = [];
        $valPD = kFP::query()
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->where([
                ['FPROD', '=', $final],
                // ['FPCNO', 'like',  $turno],
                ['FRDTE', '>=', $fecha],
                ['FRDTE', '<=', $totalF],
                // ['FTYPE', '=', 'P']
            ])
            ->get()->toarray();
        $valSD = Fso::query()
            ->select('SDDTE', 'SQREQ', 'SOCNO')
            ->where('SPROD', '=', $final)
            ->where('SDDTE', '>=', $fecha)
            ->where('SDDTE', '<=', $totalF)
            ->get()->toarray();
        $MBMS = ECL::query()
            ->selectRaw('LSDTE, SUM(LQORD) as Total,CLCNO ')
            ->where([
                ['LPROD', '=', $producto],
                ['LSDTE', '>=', $fecha],
                ['LSDTE', '<=', $totalF],
                // ['CLCNO', 'Like', $turno]
            ])
            ->groupBy('LSDTE', 'CLCNO')
            ->get()->toarray();

        $RFMA = FMA::query()
            ->selectRaw('MRDTE, SUM(MQREQ) as Total')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '>=', $fecha],
                ['MRDTE', '<=', $totalF],
            ])
            ->groupBy('MRDTE')
            ->get()->toarray();

        $RKMR = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '>=', $fecha],
                ['MRDTE', '<=', $totalF],
                //   ['MRCNO', 'Like', $turno]
            ])->groupBy('MRDTE', 'MRCNO')
            ->get()->toarray();

        $contF1 = self::contcargarF1($final);
        $valD = [];
        if ($contF1 > 1) {
            $F1 = self::cargarF1($final);
            $padres1 = array_column($F1, 'final');
            $cade = implode("' OR  MPROD='", $padres1);
            $cade1 = implode("' OR  LPROD='", $padres1);
            $requiN = '';
            $requiD = '';
            $requiD = $requiD . "  LPROD= '" .  $cade1 . "'";
            $requiN = $requiN . " MPROD= '" .  $cade . "'";

            $valD = self::Forecast($requiN, $dia, '%D%', $dias);
            $FdateD = array_column($valD, 'MRDTE');
            $FturnoD = array_column($valD, 'MRCNO');
            $FQTyD = array_column($valD, 'MQTY');
        }
        $total = 0;
        $totalf = 0;
        $totalk = 0;
        $totalN = 0;
        $totalkN = 0;
        echo "<script>console.log('Connuewk: " . $producto . "/" . $final . "' );</script>";
        while ($connt < $dias) {
            $DMBMS = array_column($MBMS, 'LSDTE');
            $VMBMS = array_column($MBMS, 'TOTAL');
            $TurnoS = array_column($MBMS, 'CLCNO');
            if (is_int(array_search($dia, $DMBMS))) {
                $valtp = substr($TurnoS[array_search($dia, $DMBMS)], 4, 1);
                if ($valtp == 'D') {
                    $total = $total + $VMBMS[array_search($dia, $DMBMS)];
                } else {
                    $totalN = $totalN + $VMBMS[array_search($dia, $DMBMS)];
                }
                unset($DMBMS[array_search($dia, $DMBMS)]);
                if (is_int(array_search($dia, $DMBMS))) {
                    $valtp = substr($TurnoS[array_search($dia, $DMBMS)], 4, 1);
                    if ($valtp == 'D') {
                        $total = $total + $VMBMS[array_search($dia, $DMBMS)];
                    } else {
                        $totalN = $totalN + $VMBMS[array_search($dia, $DMBMS)];
                    }
                }
            }
            $DFMA = array_column($RFMA, 'MRDTE');
            $VFMA = array_column($RFMA, 'TOTAL');
            if (is_int(array_search($dia, $DFMA))) {
                $totalf = $totalf + $VFMA[array_search($dia, $DFMA)];
            }

            $DKMR = array_column($RKMR, 'MRDTE');
            $VKMR = array_column($RKMR, 'TOTAL');
            $TurnoK = array_column($RKMR, 'MRCNO');
            if (is_int(array_search($dia, $DKMR))) {
                $valtp = substr($TurnoK[array_search($dia,  $DKMR)], 4, 1);
                if ($valtp == 'D') {
                    $totalk = $totalk + $VKMR[array_search($dia, $DKMR)];
                } else {
                    $totalkN = $totalkN + $VKMR[array_search($dia, $DKMR)];
                }
                unset($DKMR[array_search($dia,  $DKMR)]);
                if (is_int(array_search($dia, $DKMR))) {
                    $valtp = substr($TurnoK[array_search($dia,  $DKMR)], 4, 1);
                    $totalkN =  $totalkN + $VKMR[array_search($dia, $DKMR)];
                }
            }
            if (count($valPD) > 0) {
                $PdateD = array_column($valPD, 'FRDTE');
                $PqtyD = array_column($valPD, 'FQTY');
                $PturD = array_column($valPD, 'FPCNO');
                $PtypeD = array_column($valPD, 'FTYPE');
                if (is_int(array_search($dia, $PdateD))) {
                    $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                    $valtpp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                    $valTotal += ['P' . $dia .  $valtpp => $val5];
                    unset($PdateD[array_search($dia, $PdateD)]);
                    if (is_int(array_search($dia, $PdateD))) {
                        $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                        $valtpp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                        $valTotal += [$PtypeD[array_search($dia, $PdateD)] . $dia .  $valtpp => $val5];
                    }
                }
            }
            if (count($valSD) > 0) {
                $SdateD = array_column($valSD, 'SDDTE');
                $SqtyD = array_column($valSD, 'SQREQ');
                $SturD = array_column($valSD, 'SOCNO');
                $val7D = 0;
                $val7N = 0;
                while (is_int(array_search($dia, $SdateD))) {
                    $valtp = substr($SturD[array_search($dia, $SdateD)], 4, 1);
                    if ($valtp == 'D') {
                        $val7D = $SqtyD[array_search($dia, $SdateD)] + $val7D;
                    } else {
                        $val7N = $SqtyD[array_search($dia, $SdateD)] + $val7N;
                    }
                    unset($SdateD[array_search($dia,  $SdateD)]);
                }
                if ($val7N != 0) {
                    $valTotal += ['S' . $dia . 'N'  => $val7N];
                }
                if ($val7D != 0) {
                    $valTotal += ['S' . $dia . 'D'  => $val7D];
                }
            }

            $ttN = $totalN  + $totalkN;

            $tt = $total + $totalf + $totalk;
            if ($tt != 0) {
                $valTotal += ['R' . $dia . 'D' => $tt];
            }
            if ($ttN != 0) {
                $valTotal += ['R' . $dia . 'N' => $ttN];
            }
            // if ($contF1 > 1) {

            //     if (is_int(array_search($dia, $FdateD))) {

            //         $valtp = substr($FturnoD[array_search($dia, $FdateD)], 4, 1);
            //         $valTotal += ['F' . $dia .  $valtp => $FQTyD[array_search($dia, $FdateD)] + 0];
            //         unset($FdateD[array_search($dia, $FdateD)]);
            //         if (is_int(array_search($dia, $FdateD))) {
            //             $valtp = substr($FturnoD[array_search($dia, $FdateD)], 4, 1);
            //             $valTotal  += ['F' . $dia .   $valtp => $FQTyD[array_search($dia, $FdateD)] + 0];
            //             unset($FdateD[array_search($dia, $FdateD)]);
            //         }
            //     }
            //     $requiTD = self::requerimiento($requiD, $requiN, $dia, '%D%');
            //     $valTotal  += ['R' . $dia . 'D' => $requiTD['D']];
            //     $valTotal  += ['R' . $dia . 'N' => $requiTD['N']];
            //     dd('ksndk', $requiTD,);
            // } else {

            //     if (count($valD) > 1) {
            //         $MdateD = array_column($valD, 'MRDTE');
            //         $Mqty = array_column($valD, 'MQTY');
            //         $mturno = array_column($valD, 'MRCNO');
            //         $valt = 0;
            //         if (is_int(array_search($dia, $MdateD))) {
            //             $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
            //             $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
            //             $valTotal  += ['F' . $dia . $valt => $val1];
            //             unset($MdateD[array_search($dia, $MdateD)]);
            //             if (is_int(array_search($dia, $MdateD))) {
            //                 $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
            //                 $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
            //                 $valTotal  += ['F' . $dia . $valt => $val1];
            //             }
            //         }
            //         dd('sino', $valTotal);
            //     }
            // }


            $total = 0;
            $totalf = 0;
            $totalk = 0;
            $totalN = 0;
            $totalkN = 0;

            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
        }

        return $valTotal;
    }
}
