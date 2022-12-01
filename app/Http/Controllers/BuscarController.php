<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZCC;
use App\Models\KMR;
use App\Models\kFP;
use App\Models\frt;
use App\Models\Iim;
use App\Models\LOGSUP;
use App\Models\Fma;
use App\Models\Ecl;
use App\Models\MBMr;
use App\Models\Fso;
use App\Models\YK006;
use App\Models\Structure;
use Carbon\Carbon;
use registros;
use App\Exports\PlanExport;
use Maatwebsite\Excel\Facades\Excel;

class BuscarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $plan = array();
        $NP = '*';
        $TP = 'NO';
        $CP = '';
        $WC = '';
        $this->WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();

        return view('planeacion.buscar', ['LWK' => $this->WCs, 'fecha' => $fecha, 'tp' => $TP, 'NP' => $NP, 'dias' => $dias, 'plan' => $plan]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $NP = $request->NP ?? 'x';
        $plan = '';
        $TP = $request->SeProject ?? 'NO';
        $inF1 = array();
        $this->WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();

        $datos = self::CargarforcastF1($NP, $fecha, $dias);
        $inF1 += [$NP =>  $datos];
        return view('planeacion.buscar', ['LWK' => $this->WCs, 'fecha' => $fecha, 'tp' => $TP, 'NP' => $NP, 'dias' => $dias, 'plan' => $inF1]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $NP = $request->NP ?? 'x';
        $plan = '';
        $TP = $request->SeProject ?? 'NO';
        $inF1 = array();
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

        // $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        // $query = "CALL LX834OU02.YMP006C";
        // $result = odbc_exec($conn, $query);
        $datos = self::CargarforcastF1($NP, $fecha, $dias);
        $inF1 += [$NP =>  $datos];
        $this->WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();
        return view('planeacion.buscar', ['LWK' => $this->WCs, 'fecha' => $fecha, 'tp' => $TP, 'NP' => $NP, 'dias' => $dias, 'plan' => $inF1]);
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
    function CargarforcastF1($prod, $hoy, $dias)
    {

        $inF1 = array();
        $total = array();
        $dia = $hoy;
        $connt = 1;
        $valD = self::Forecasttotal($prod, $dia, '%D%', $dias );
        $valPD = self::planTotal($prod, $dia, '%D%', $dias);
        // $valFD = self::FirmeTotal($prod, $dia, '%D%', $dias);
        $valSD = self::ShopOTotal($prod, $dia, '%D%', $dias);
        $inF1 += ['parte' => $prod];
        while ($connt <= $dias) {
            if (count($valD) != 0) {
                $MdateD = array_column($valD, 'MRDTE');
                $Mqty = array_column($valD, 'MQTY');
                $mturno = array_column($valD, 'MRCNO');
                if (is_int(array_search($dia, $MdateD))) {
                    $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                    $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
                    $inF1 += ['F' . $dia . $valt => $val1];
                    // $inF1 += ['F' . $dia . 'D' => $val1];
                    unset($MdateD[array_search($dia, $MdateD)]);
                    if (is_int(array_search($dia, $MdateD))) {
                        $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                        $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
                        $inF1 += ['F' . $dia . $valt => $val1];
                        // $inF1 += ['F' . $dia . 'D' => $val1];
                    }
                }
            }
            if (count($valPD) != 0) {
                $PdateD = array_column($valPD, 'FRDTE');
                $PqtyD = array_column($valPD, 'FQTY');
                $PturD = array_column($valPD, 'FPCNO');
                $PtypeD = array_column($valPD, 'FTYPE');
                if (is_int(array_search($dia, $PdateD))) {
                    $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                    $valtp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                    $inF1 += ['P' . $dia .  $valtp => $val5];
                    unset($PdateD[array_search($dia, $PdateD)]);
                    if (is_int(array_search($dia, $PdateD))) {
                        $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                        $valtp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                        $inF1 += [$PtypeD[array_search($dia, $PdateD)] . $dia .  $valtp => $val5];
                    }
                }
            }
            if (count($valSD) != 0) {
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
                $inF1 += ['S' . $dia . 'N'  => $val7N];
                $inF1 += ['S' . $dia . 'D'  => $val7D];
            }
            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
        }
        $contsub = self::contcargar($prod);
        if ($contsub != 0) {
            $datossub = self::Cargarforcast($prod, $hoy, $dias,$valD );
            $inF1 += ['hijos' . $prod =>  $datossub];
        }

        return $inF1;
    }

    function Cargarforcast($prod1, $hoy, $dias,$Fpadre)
    {
        $Sub = self::cargar($prod1);
        $inF1 = array();
        $total = array();

        foreach ($Sub as $subs) {
            $prod = $subs['Componente'];
            $inF1 = [
                'sub' => $prod,
            ];
            $dia = $hoy;
            $connt = 1;
            $valRD = self::RequeTotalh($prod1, $dia, '%D%', $dias);
                $inF1 += $valRD;
            $valPD = self::planTotal($prod, $dia, '%D%', $dias);
            $valSD = self::ShopOTotal($prod, $dia, '%D%', $dias);
            $contF1 = self::contcargarF1($prod);
            if($contF1 > 1)
            {
                $F1 = self::cargarF1($prod);
                $requiN = '';
                $requiD = '';
                foreach ($F1 as $F1s) {
                    if ($requiN != '') {
                        $requiD =   $requiD . ' OR ';
                        $requiN =  $requiN . ' OR ';
                    }
                    $padres = '';
                    $pF = $F1s['final'];
                    $padres = $padres . $pF . ',';
                    $requiD = $requiD . "  LPROD= '" . $pF . "'";
                    $requiN = $requiN . " MPROD= '" . $pF . "'";
                }
                $valD = self::Forecast($requiN, $dia, '%D%',$dias);
                // $valN = self::Forecast($requiN, $dia, '%N%') + 0;
                    $FdateD = array_column($valD, 'MRDTE');
                    $FturnoD = array_column($valD, 'MRCNO');
                    $FQTyD = array_column($valD, 'MQTY');
            }
            while ($connt <= $dias) {
                if ($contF1 > 1) {
                        if(is_int(array_search($dia,$FdateD)))
                        {
                            $valtp = substr($FturnoD[array_search($dia,$FdateD)], 4, 1);
                            $inF1 += ['F'. $dia .  $valtp => $FQTyD[array_search($dia, $FdateD)]+0];
                            unset($FdateD[array_search($dia, $FdateD)]);
                            if(is_int(array_search($dia,$FdateD)))
                        {
                            $valtp = substr($FturnoD[array_search($dia,$FdateD)], 4, 1);
                            $inF1 += ['F'. $dia .   $valtp => $FQTyD[array_search($dia, $FdateD)]+0];
                            unset($FdateD[array_search($dia, $FdateD)]);
                        }
                    }
                    $requiTD = self::requerimiento($requiD, $requiN, $dia, '%D%');
                    $requiTN = self::requerimiento($requiD, $requiN, $dia, '%N%');
                    $inF1 += ['R' . $dia . 'D' => $requiTD];
                    $inF1 += ['R' . $dia . 'N' => $requiTN];
                } else {
                    // $valD = self::Forecasttotal($prod1, $dia, '%D%', $dias);
                    if(count($Fpadre)>0)
                    {
                        $MdateD = array_column($Fpadre, 'MRDTE');
                        $Mqty = array_column($Fpadre, 'MQTY');
                        $mturno = array_column($Fpadre, 'MRCNO');
                        $valt = 0;
                        if (is_int(array_search($dia, $MdateD))) {
                            $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                            $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
                            $inF1 += ['F' . $dia . $valt => $val1];
                            unset($MdateD[array_search($dia, $MdateD)]);
                            if (is_int(array_search($dia, $MdateD))) {
                                $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                                $valt = substr($mturno[array_search($dia, $MdateD)], 4, 1);
                                $inF1 += ['F' . $dia . $valt => $val1];
                            }
                        }

                    }

                }
                    if(count($valPD)>0)
                    {
                        $PdateD = array_column($valPD, 'FRDTE');
                        $PqtyD = array_column($valPD, 'FQTY');
                        $PturD = array_column($valPD, 'FPCNO');
                        $PtypeD = array_column($valPD, 'FTYPE');
                        if (is_int(array_search($dia, $PdateD))) {
                            $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                            $valtp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                            $inF1 += ['P' . $dia .  $valtp => $val5];
                            unset($PdateD[array_search($dia, $PdateD)]);
                            if (is_int(array_search($dia, $PdateD))) {
                                $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                                $valtp = substr($PturD[array_search($dia, $PdateD)], 4, 1);
                                $inF1 += [$PtypeD[array_search($dia, $PdateD)] . $dia .  $valtp => $val5];
                            }
                        }
                    }

                    if(count($valSD)>0)
                    {
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
                $inF1 += ['S' . $dia . 'N'  => $val7N];
                $inF1 += ['S' . $dia . 'D'  => $val7D];
            }
                $dia = $dia = date('Ymd', strtotime($dia . '+1 day'));
                $connt++;
            }


            array_push($total, $inF1);
        }

        return $total;
    }

    function Forecast($producto, $fecha, $turno,$dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $plan = kmr::query()
            ->select('MQTY' ,'MRDTE','MRCNO')
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
    function contcargar($prod)
    {
        $res = Structure::query()
            ->select('Final')
            ->where('Final', $prod)
            ->where('clase', '!=', '01')
            ->count();
        return $res;
    }
    function cargarF1($prod)
    {
        $res = Structure::query()
            ->select('final', 'componente')
            ->where('componente', $prod)
            ->distinct('final')
            ->get()->toArray();
        return $res;
    }
    function contcargarF1($prod)
    {
        $res = Structure::query()
            ->select('Final', 'componente')
            ->where('componente', $prod)
            ->count();
        return $res;
    }
    function RequeTotalh($producto, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $dia = $fecha;
        $connt = 0;
        $valTotal  = [];
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
        if ($turno == '%D%') {
            $RFMA = FMA::query()
                ->selectRaw('MRDTE, SUM(MQREQ) as Total')
                ->where([
                    ['MPROD', '=', $producto],
                    ['MRDTE', '>=', $fecha],
                    ['MRDTE', '<=', $totalF],
                ])
                ->groupBy('MRDTE')
                ->get()->toarray();
        }
        $RKMR = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '>=', $fecha],
                ['MRDTE', '<=', $totalF],
                //   ['MRCNO', 'Like', $turno]
            ])->groupBy('MRDTE', 'MRCNO')
            ->get()->toarray();
        $total = 0;
        $totalf = 0;
        $totalk = 0;
        $totalN = 0;
        $totalkN = 0;
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
            $ttN = $totalN  + $totalkN;
            $tt = $total + $totalf + $totalk;
            $valTotal += ['R' . $dia . 'D' => $tt];
            $valTotal += ['R' . $dia . 'N' => $ttN];


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
    function requerimiento($Lproducto, $Mproducto, $fecha, $turno)
    {
        $MBMS = 0;
        $RFMA = 0;
        $RKM = 0;

        if ($turno == '%D%') {
            $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
            $query = "SELECT TECL,TF,TKMR,T.LPROD as prod FROM (select sum(LQORD)AS TECL, LSDTE,LPROD
            from LX834F01.ECL
            where  (" . $Lproducto . "  ) and LSDTE = '" . $fecha . "'
            AND CLCNO LIKE '" . $turno . "'
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

            $sumre =  odbc_result($result, 'TECL') + odbc_result($result, 'TF') + odbc_result($result, 'TKMR') + 0;
        } else {
            $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
            $query = "SELECT TECL,TKMR,T.LPROD as prod FROM (select sum(LQORD)AS TECL, LSDTE,LPROD
            from LX834F01.ECL
            where  (" . $Lproducto . "  )
            and LSDTE = '" . $fecha . "'
            AND CLCNO LIKE '" . $turno . "'
            GROUP BY LSDTE,LPROD ) T
            full OUTER JOIN (SELECT sum(MQTY) AS TKMR,MRDTE AS fecha,MPROD
            FROM LX834F01.KMR
            WHERE MRDTE='" . $fecha . "'
            AND  (" . $Mproducto . ")
            AND MRCNO LIKE '" . $turno . "'
            GROUP BY  MRDTE,MPROD) K ON K.MPROD=T.LPROD";
            $result = odbc_exec($conn, $query);
            $sumre =  odbc_result($result, 'TECL') + odbc_result($result, 'TKMR') + 0;
        }


        return  $sumre;
    }
    function cargar($prod)
    {
        $res = Structure::query()
            ->select('Final', 'Componente', 'Activo')
            ->where('Final', $prod)
            ->where([
                ['clase', '!=', '01'],
                ['Activo', '1'],
            ])
            ->get()->toArray();

        return $res;
    }
}
