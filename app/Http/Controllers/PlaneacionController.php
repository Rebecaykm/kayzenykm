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

use App\Models\Fma;
use App\Models\Ecl;
use App\Models\MBMr;

use App\Models\Fso;

use App\Models\YK006;
use App\Models\Structure;
use Carbon\Carbon;
use registros;


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

        $dias = $request->dias ?? '5';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $plan = '';
        $TP = 'NO';
        $CP = '';
        $WC = '';
        $this->WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();




        return view('planeacion.index', ['LWK' => $this->WCs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $inF1 = array();
        $dias = $request->dias ?? '7';
        $fecha = $request->fecha != '' ? Carbon::parse($request->fecha)->format('Ymd') : Carbon::now()->format('Ymd');
        $TP = $request->SeProject;
        $CP = $request->SePC;
        $WC = $request->SeWC;
        $plan = Iim::query()
            ->select('IPROD', 'ICLAS', 'IMBOXQ')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->distinct('IPROD')
            ->simplePaginate(3)->withQueryString();
        $total = array();
        foreach ($plan as $WCss) {
            $datos = self::CargarforcastF1($WCss->IPROD, $fecha, $dias);
            $inF1 += [$WCss->IPROD =>  $datos];
        }


        return view('planeacion.plancomponente', ['plan' => $inF1, 'plantotal' => $plan, 'tp' => $TP, 'cp' => $CP, 'wc' => $WC, 'fecha' => $fecha, 'dias' => $dias]);
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
                $fefin = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
                $WCT = Frt::query()
                    ->select('RWRKC', 'RPROD')
                    ->where('RPROD', $namenA)
                    ->value('RWRKC');
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
                $hoy = date('Ymd', strtotime($hoy . '+1 day'));
            }
        }
        $plan = Iim::query()
            ->select('IPROD', 'ICLAS', 'IMBOXQ')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->distinct('IPROD')
            ->simplePaginate(3)->withQueryString();
        $total = array();
        foreach ($plan as $WCss) {
            $datos = self::CargarforcastF1($WCss->IPROD, $fecha, $dias);
            $inF1 += [$WCss->IPROD =>  $datos];
        }
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
        $res = Structure::query()
            ->select('Final')
            ->where('Final', $prod)
            ->where('clase', '!=', '01')
            ->count();
        return $res;
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
    function cargarestructura($prod)
    {
        $res = Structure::query()
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
    function CargarforcastF1($prod, $hoy, $dias)
    {

        $inF1 = array();
        $total = array();
        $dia = $hoy;
        $connt = 1;

        $valD = self::Forecasttotal($prod, $dia, '%D%', $dias);
        $valPD = self::planTotal($prod, $dia, '%D%', $dias);
        $valFD = self::FirmeTotal($prod, $dia, '%D%', $dias);
        $valN = self::Forecasttotal($prod, $dia, '%N%', $dias);

        $valPN = self::planTotal($prod, $dia, '%N%', $dias);
        $valFN = self::FirmeTotal($prod, $dia, '%N%', $dias);
        $valSD = self::ShopOTotal($prod, $dia, '%D%', $dias);
        $valSN = self::ShopOTotal($prod, $dia, '%N%', $dias);
        $valRD = self::RequeTotal($prod, $dia, '%D%', $dias);
        $valRN = self::RequeTotal($prod, $dia, '%N%', $dias);

        $inF1 += ['parte' => $prod];

        while ($connt <= $dias) {


            $MdateD = array_column($valD, 'MRDTE');
            $Mqty = array_column($valD, 'MQTY');
            if (is_int(array_search($dia, $MdateD))) {

                $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                $inF1 += ['F' . $dia . 'D' => $val1];
            }

            $MdateN = array_column($valN, 'MRDTE');
            $Mqty = array_column($valN, 'MQTY');
            if (is_int(array_search($dia, $MdateN))) {

                $val2 = $Mqty[array_search($dia, $MdateN)] + 0;
                $inF1 += ['F' . $dia . 'N' => $val2];
            }


            $FdateD = array_column($valFD, 'FRDTE');
            $FqtyD = array_column($valFD, 'FQTY');
            if (is_int(array_search($dia, $FdateD))) {

                $val3 = $FqtyD[array_search($dia, $FdateD)] + 0;
                $inF1 += ['Fi' . $dia . 'D' => $val3];
            }

            $FdateN = array_column($valFN, 'FRDTE');
            $FqtyN = array_column($valFN, 'FQTY');
            if (is_int(array_search($dia, $FdateN))) {

                $val4 = $FqtyN[array_search($dia,  $FdateN)] + 0;
                $inF1 += ['Fi' . $dia . 'N' => $val4];
            }

            $PdateD = array_column($valPD, 'FRDTE');
            $PqtyD = array_column($valPD, 'FQTY');
            if (is_int(array_search($dia, $PdateD))) {

                $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                $inF1 += ['P' . $dia . 'D' => $val5];
            }


            $PdateN = array_column($valPN, 'FRDTE');
            $PqtyN = array_column($valPN, 'FQTY');
            if (is_int(array_search($dia, $PdateN))) {
                $val6 = $PqtyN[array_search($dia, $PdateN)] + 0;
                $inF1 += ['P' . $dia . 'N' => $val6];
            }

            $SdateD = array_column($valSD, 'SDDTE');
            $SqtyD = array_column($valSD, 'SQREQ');

            if (is_int(array_search($dia, $SdateD))) {

                $val7 = $SqtyD[array_search($dia, $SdateD)] + 0;
                $inF1 += ['S' . $dia . 'D' => $val7];
            }

            $SdateN = array_column($valSN, 'SDDTE');
            $SqtyN = array_column($valSN, 'SQREQ');
            if (is_int(array_search($dia, $SdateN))) {

                $val8 = $SqtyN[array_search($dia, $SdateN)] + 0;
                $inF1 += ['S' . $dia . 'N' => $val8];
            }

            $inF1 += ['R' . $dia . 'D' => $valRD[$dia]];
            $inF1 += ['R' . $dia . 'N' => $valRN[$dia]];



            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
        }
        $contsub = self::contcargar($prod);
        if ($contsub != 0) {
            $datossub = self::Cargarforcast($prod, $hoy, $dias);
            $inF1 += ['hijos' . $prod =>  $datossub];
        }


        return $inF1;
    }

    function Cargarforcast($prod1, $hoy, $dias)
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
            $valD = self::Forecasttotal($prod1, $dia, '%D%', $dias);
            $valN = self::Forecasttotal($prod1, $dia, '%N%', $dias);
            $valRD = self::RequeTotalh($prod1, $dia, '%D%', $dias);
            $valRN = self::RequeTotalh($prod1, $dia, '%N%', $dias);
            $valPD = self::planTotal($prod, $dia, '%D%', $dias);
            $valFD = self::FirmeTotal($prod, $dia, '%D%', $dias);
            $valPN = self::planTotal($prod, $dia, '%N%', $dias);
            $valFN = self::FirmeTotal($prod, $dia, '%N%', $dias);
            $valSD = self::ShopOTotal($prod, $dia, '%D%', $dias);
            $valSN = self::ShopOTotal($prod, $dia, '%N%', $dias);

            while ($connt <= $dias) {
                $contF1 = self::contcargarF1($prod);

                if ($contF1 > 1) {
                    $padres = '';
                    $tD = 0;
                    $tN = 0;
                    $requiN = 0;
                    $requiD = 0;
                    $F1 = self::cargarF1($prod);
                    foreach ($F1 as $F1s) {
                        $pF = $F1s['final'];
                        $padres = $padres . $pF . ',';
                        $valD = self::Forecast($pF, $dia, '%D%');
                        $valN = self::Forecast($pF, $dia, '%N%');
                        $requiTD = self::requerimiento($pF, $dia, '%D%');
                        $requiTN = self::requerimiento($pF, $dia, '%N%');
                        $tD = $valD + $tD;
                        $tN = $valN + $tN;
                        $requiD = $requiD + $requiTD;
                        $requiN = $requiN + $requiTN;
                    }

                    $inF1 += ['F' . $dia . 'D' => $valD];
                    $inF1 += ['F' . $dia . 'N' => $valN];
                    $inF1 += ['R' . $dia . 'D' => $requiD];
                    $inF1 += ['R' . $dia . 'N' => $requiN];
                } else {
                    $MdateD = array_column($valD, 'MRDTE');
                    $Mqty = array_column($valD, 'MQTY');

                    if (is_int(array_search($dia, $MdateD))) {
                        $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                        $inF1 += ['F' . $dia . 'D' => $val1];
                    }

                    $MdateN = array_column($valN, 'MRDTE');
                    $Mqty = array_column($valN, 'MQTY');
                    if (is_int(array_search($dia, $MdateN))) {
                        $val2 = $Mqty[array_search($dia, $MdateN)] + 0;
                        $inF1 += ['F' . $dia . 'N' => $val2];
                    }
                    $inF1 += ['R' . $dia . 'D' => $valRD[$dia]];
                    $inF1 += ['R' . $dia . 'N' => $valRN[$dia]];
                }

                $FdateD = array_column($valFD, 'FRDTE');
                $FqtyD = array_column($valFD, 'FQTY');
                if (is_int(array_search($dia, $FdateD))) {
                    $val3 = $FqtyD[array_search($dia, $FdateD)] + 0;
                    $inF1 += ['Fi' . $dia . 'D' => $val3];
                }


                $FdateN = array_column($valFN, 'FRDTE');
                $FqtyN = array_column($valFN, 'FQTY');
                if (is_int(array_search($dia, $FdateN))) {

                    $val4 = $FqtyN[array_search($dia,  $FdateN)] + 0;
                    $inF1 += ['Fi' . $dia . 'N' => $val4];
                }




                $PdateD = array_column($valPD, 'FRDTE');
                $PqtyD = array_column($valPD, 'FQTY');
                if (is_int(array_search($dia, $PdateD))) {

                    $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                    $inF1 += ['P' . $dia . 'D' => $val5];
                }

                $PdateN = array_column($valPN, 'FRDTE');
                $PqtyN = array_column($valPN, 'FQTY');
                if (is_int(array_search($dia, $PdateN))) {

                    $val6 = $PqtyN[array_search($dia, $PdateN)] + 0;
                    $inF1 += ['P' . $dia . 'N' => $val6];
                }

                $SdateD = array_column($valSD, 'SDDTE');
                $SqtyD = array_column($valSD, 'SQREQ');
                if (is_int(array_search($dia, $SdateD))) {

                    $val7 = $SqtyD[array_search($dia, $SdateD)] + 0;
                    $inF1 += ['S' . $dia . 'D' => $val7];
                }

                $SdateN = array_column($valSN, 'SDDTE');
                $SqtyN = array_column($valSN, 'SQREQ');
                if (is_int(array_search($dia, $SdateN))) {

                    $val8 = $SqtyN[array_search($dia, $SdateN)] + 0;
                    $inF1 += ['S' . $dia . 'N' => $val8];
                }
                $dia = $dia = date('Ymd', strtotime($dia . '+1 day'));
                $connt++;
            }
            array_push($total, $inF1);
        }

        return $total;
    }


    // ---------------------------------guardar estructuras de BOM----------------------------------------------
    function guardar($prod, $sub, $clase)
    {
        $res = self::buscar($prod, $sub);
        if ($res == 0) {
            $data = Structure::create([
                'final' => $prod,
                'componente' => $sub,
                'clase' => $clase,
                'Activo' => '1',
            ]);
        }
    }

    function buscar($prod, $sub)
    {
        $data = Structure::query()
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

    function Forecast($producto, $fecha, $turno)
    {

        $plan = kmr::query()
            ->select('MQTY')
            ->where('MRDTE', '=', $fecha)
            ->where('MPROD', '=', $producto)
            ->where('MRCNO', 'like', $turno)
            ->sum('MQTY');
        return $plan;
    }
    function ForecastTOTAL($producto, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));


        $plan = kmr::query()
            ->select('MRDTE', 'MQTY')
            ->where('MRDTE', '>=', $fecha)
            ->where('MRDTE', '<=', $totalF)
            ->where('MPROD', '=', $producto)
            ->where('MRCNO', 'like', $turno)
            ->get()->toarray();
        return $plan;
    }
    function planyfirme($pro, $fecha, $turno)
    {

        $kfps = kFP::query()
            ->select('FTYPE')
            ->where('FPROD', '=', $pro)
            ->where('FPCNO', 'like', $turno)
            ->where('FRDTE', '=', $fecha)
            ->selectRaw("SUM(FQTY) as total")
            ->groupby('FTYPE')->first()->toarray();
        return $kfps;
    }
    function contplanyfirme($pro, $fecha, $turno)
    {

        $kfps = kFP::query()
            ->select('FTYPE')
            ->where('FPROD', '=', $pro)
            ->where('FPCNO', 'like', $turno)
            ->where('FRDTE', '=',  $fecha)
            ->selectRaw("SUM(FQTY) as total")
            ->groupby('FTYPE')->count();
        return $kfps;
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
            ->select('FRDTE', 'FQTY')
            ->where([
                ['FPROD', '=', $pro],
                ['FPCNO', 'like',  $turno],
                ['FRDTE', '>=', $fecha],
                ['FRDTE', '<=', $totalF],
                ['FTYPE', '=', 'P']
            ])
            ->get()->toarray();


        return $kfps;
    }

    function Firme($pro, $fecha, $turno)
    {
        $kfps = kFP::query()
            ->select('FQTY')
            ->where('FPROD', '=', $pro)
            ->where('FPCNO', 'like', $turno)
            ->where('FRDTE', '=', $fecha)
            ->where('FTYPE', '=', 'F')
            ->sum('FQTY');
        return $kfps;
    }
    function FirmeTotal($pro, $fecha, $turno, $dias)
    {
        $totalF = date('Ymd', strtotime($fecha . '+' . $dias . ' day'));
        $kfps = kFP::query()
            ->select('FRDTE', 'FQTY')
            ->where('FPROD', '=', $pro)
            ->where('FPCNO', 'like', $turno)
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
            ->select('SDDTE', 'SQREQ')
            ->where('SPROD', '=', $pro)
            ->where('SOCNO', 'like', $turno)
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

    function requerimiento($producto, $fecha, $turno)
    {
        $MBMS = 0;
        $RFMA = 0;
        $RKM = 0;
        $MBMS = ECL::query()
            ->select('LPROD', 'LQORD', 'LSDTE', 'CLCNO')
            ->where([
                ['LPROD', '=', $producto],
                ['LSDTE', '=', $fecha],
                ['CLCNO', 'Like', $turno]
            ])
            ->count();



        $MBMS = ECL::query()
            ->select('LPROD', 'LQORD', 'LSDTE', 'CLCNO')
            ->where([
                ['LPROD', '=', $producto],
                ['LSDTE', '=', $fecha],
                ['CLCNO', 'Like', $turno]
            ])
            ->sum('LQORD');

        if ($turno == '%D%') {
            $RFMA = FMA::query()
                ->select('MPROD', 'MQREQ', 'MRDTE')
                ->where([
                    ['MPROD', '=', $producto],
                    ['MRDTE', '=', $fecha],
                ])
                ->sum('MQREQ');
        }
        $RKMR = KMR::query()
            ->select('MQTY', 'MRDTE', 'MRCNO')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '=', $fecha],
                ['MRCNO', 'Like', $turno]
            ])
            ->sum('MQTY');
        $sumre = $MBMS + $RFMA + $RKMR;
        return  $sumre;
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
    function RequeTotalh($producto, $fecha, $turno, $dias)
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
                    ['MRDTE', '<=', $totalF],
                ])
                ->groupBy('MRDTE')
                ->get()->toarray();
        }
        $RKMR = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '>=', $fecha],
                ['MRDTE', '<=', $totalF],
                ['MRCNO', 'Like', $turno]
            ])->groupBy('MRDTE')
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
            $dia . '<br>';

            $tt = $total + $totalf + $totalk;
            $valTotal += [$dia => $tt];

            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
        }

        return $valTotal;
    }
}
