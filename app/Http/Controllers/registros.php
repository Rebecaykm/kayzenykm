<?php


use App\Models\LWK;
use App\Models\IPB;
use App\Models\Kmr;
use App\Models\Fma;
use App\Models\Ecl;
use App\Models\kFP;
use App\Models\Fso;
use App\Models\Iim;
use App\Models\Fpo;
use App\Models\MBMr;
use App\Models\ZCC;
use App\Models\Structure;
use Illuminate\Support\Arr;

date_default_timezone_set('America/Monterrey');
// include_once("conexionloc.php");

class registros
{
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
        $valPD = self::planTotal($prod, $dias, '%D%', $dias);
        $valFD = self::FirmeTotal($prod, $dia, '%D%', $dias);
        $valN = self::Forecasttotal($prod, $dia, '%N%', $dias);
        $valPN = self::planTotal($prod, $dia, '%N', $dias);
        $valFN = self::FirmeTotal($prod, $dia, '%N%', $dias);
        $valSD = self::ShopOTotal($prod, $dia, '%D%', $dias);
        $valSN = self::ShopOTotal($prod, $dia, '%N%', $dias);
        $valRD = self::RequeTotal($prod, $dia, '%D%', $dias);
        $valRN = self::RequeTotal($prod, $dia, '%N%', $dias);


        while ($connt <= $dias) {
            $inF1 = [
                'Dia' => $dia,
            ];
            $MdateD = array_column($valD, 'MRDTE');
            $Mqty = array_column($valD, 'MQTY');
            if (array_search($dia, $MdateD) == false) {
                // $inF1 += ['F' . $dia . 'D' => 0];
            } else {
                $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                $inF1 += ['F' . $dia . 'D' => $val1];
            }

            $MdateN = array_column($valN, 'MRDTE');
            $Mqty = array_column($valN, 'MQTY');
            if (array_search($dia, $MdateN) == false) {
                // $inF1 += ['F' . $dia . 'N' => 0];
            } else {
                $val2 = $Mqty[array_search($dia, $MdateN)] + 0;
                $inF1 += ['F' . $dia . 'N' => $val2];
            }


            $FdateD = array_column($valFD, 'FRDTE');
            $FqtyD = array_column($valFD, 'FQTY');
            if (array_search($dia, $FdateD) == false) {
                //  $inF1 += ['Fi' . $dia . 'D' => 0];
            } else {
                $val3 = $FqtyD[array_search($dia, $FdateD)] + 0;
                $inF1 += ['Fi' . $dia . 'D' => $val3];
            }

            $FdateN = array_column($valFN, 'FRDTE');
            $FqtyN = array_column($valFN, 'FQTY');
            if (array_search($dia, $FdateN) == false) {
                // $inF1 += ['Fi' . $dia . 'N' => 0];
            } else {
                $val4 = $FqtyN[array_search($dia,  $FdateN)] + 0;
                $inF1 += ['Fi' . $dia . 'N' => $val4];
            }

            $PdateD = array_column($valPD, 'FRDTE');
            $PqtyD = array_column($valPD, 'FQTY');
            if (array_search($dia, $PdateD) == false) {
                //  $inF1 += ['P' . $dia . 'D' => 0];
            } else {
                $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                $inF1 += ['P' . $dia . 'D' => $val5];
            }


            $PdateN = array_column($valPN, 'FRDTE');
            $PqtyN = array_column($valPN, 'FQTY');
            if (array_search($dia, $PdateN) == false) {
                // $inF1 += ['P' . $dia . 'N' => 0];
            } else {
                $val6 = $PqtyN[array_search($dia, $PdateN)] + 0;
                $inF1 += ['P' . $dia . 'N' => $val6];
            }

            $SdateD = array_column($valSD, 'SDDTE');
            $SqtyD = array_column($valSD, 'SQREQ');
            if (is_int(array_search($dia, $SdateD))) {

                // $inF1 += ['S' . $dia . 'D' => 0];

                $val7 = $SqtyD[array_search($dia, $SdateD)] + 0;
                $inF1 += ['S' . $dia . 'D' => $val7];
            }

            $SdateN = array_column($valSN, 'SDDTE');
            $SqtyN = array_column($valSN, 'SQREQ');
            if (array_search($dia, $SdateN) == false) {
                // $inF1 += ['S' . $dia . 'N' => 0];
            } else {
                $val8 = $SqtyN[array_search($dia, $SdateN)] + 0;
                $inF1 += ['S' . $dia . 'N' => $val8];
            }
            $inF1 += ['R' . $dia . 'D' => $valRD[$dia]];
            $inF1 += ['R' . $dia . 'N' => $valRN[$dia]];


            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
            array_push($total, $inF1);
        }

        return $total;
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
            $valRD = self::RequeTotal($prod1, $dia, '%D%', $dias);
            $valRN = self::RequeTotal($prod1, $dia, '%N%', $dias);
            $valPD = self::planTotal($prod, $dias, '%D%', $dias);
            $valFD = self::FirmeTotal($prod, $dia, '%D%', $dias);
            $valPN = self::planTotal($prod, $dia, '%N', $dias);
            $valFN = self::FirmeTotal($prod, $dia, '%N%', $dias);
            $valSD = self::ShopOTotal($prod, $dia, '%D%', $dias);
            $valSN = self::ShopOTotal($prod, $dia, '%N%', $dias);

            while ($connt <= $dias) {
                $contF1 = self::contcargarF1($prod);

                if ($contF1 > 1) {
                    $tD = 0;
                    $tN = 0;
                    $requiN = 0;
                    $requiD = 0;
                    $F1 = self::cargarF1($prod);
                    foreach ($F1 as $F1s) {
                        $pF = $F1s['final'];
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
                    if (array_search($dia, $MdateD) == false) {
                        // $inF1 += ['F' . $dia . 'D' => 0];
                    } else {
                        $val1 = $Mqty[array_search($dia, $MdateD)] + 0;
                        $inF1 += ['F' . $dia . 'D' => $val1];
                    }

                    $MdateN = array_column($valN, 'MRDTE');
                    $Mqty = array_column($valN, 'MQTY');
                    if (array_search($dia, $MdateN) == false) {
                        // $inF1 += ['F' . $dia . 'N' => 0];
                    } else {
                        $val2 = $Mqty[array_search($dia, $MdateN)] + 0;
                        $inF1 += ['F' . $dia . 'N' => $val2];
                    }
                    $inF1 += ['R' . $dia . 'D' => $valRD[$dia]];
                    $inF1 += ['R' . $dia . 'N' => $valRN[$dia]];

                }

                $FdateD = array_column($valFD, 'FRDTE');
                $FqtyD = array_column($valFD, 'FQTY');
                if (array_search($dia, $FdateD) == false) {
                    // $inF1 += ['Fi' . $dia . 'D' => 0];
                } else {
                    $val3 = $FqtyD[array_search($dia, $FdateD)] + 0;
                    $inF1 += ['Fi' . $dia . 'D' => $val3];
                }


                $FdateN = array_column($valFN, 'FRDTE');
                $FqtyN = array_column($valFN, 'FQTY');
                if (array_search($dia, $FdateN) == false) {
                    // $inF1 += ['Fi' . $dia . 'N' => 0];
                } else {
                    $val4 = $FqtyN[array_search($dia,  $FdateN)] + 0;
                    $inF1 += ['Fi' . $dia . 'N' => $val4];
                }


                $PdateD = array_column($valPD, 'FRDTE');
                $PqtyD = array_column($valPD, 'FQTY');
                if (array_search($dia, $PdateD) == false) {
                    // $inF1 += ['P' . $dia . 'D' => 0];
                } else {
                    $val5 = $PqtyD[array_search($dia, $PdateD)] + 0;
                    $inF1 += ['P' . $dia . 'D' => $val5];
                }

                $PdateN = array_column($valPN, 'FRDTE');
                $PqtyN = array_column($valPN, 'FQTY');
                if (array_search($dia, $PdateN) == false) {
                    // $inF1 += ['P' . $dia . 'N' => 0];
                } else {
                    $val6 = $PqtyN[array_search($dia, $PdateN)] + 0;
                    $inF1 += ['P' . $dia . 'N' => $val6];
                }

                $SdateD = array_column($valSD, 'SDDTE');
                $SqtyD = array_column($valSD, 'SQREQ');
                if (array_search($dia, $SdateD) == false) {
                    // $inF1 += ['S' . $dia . 'D' => 0];
                } else {
                    $val7 = $SqtyD[array_search($dia, $SdateD)] + 0;
                    $inF1 += ['S' . $dia . 'D' => $val7];
                }

                $SdateN = array_column($valSN, 'SDDTE');
                $SqtyN = array_column($valSN, 'SQREQ');
                if (array_search($dia, $SdateN) == false) {
                    // $inF1 += ['S' . $dia . 'N' => 0];
                } else {
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
            ->where('FPROD', '=', $pro)
            ->where('FPCNO', 'like', $turno)
            ->where('FRDTE', '>=', $fecha)
            ->where('FRDTE', '<=', $totalF)
            ->where('FTYPE', '=', 'P')
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

        $ECL = 0;
        if ($MBMS == 0) {
            $ECL = 0;
        } else {
            $MBMS = ECL::query()
                ->select('LPROD', 'LQORD', 'LSDTE', 'CLCNO')
                ->where([
                    ['LPROD', '=', $producto],
                    ['LSDTE', '=', $fecha],
                    ['CLCNO', 'Like', $turno]
                ])
                ->sum('LQORD');
        }

        $RFMA = FMA::query()
            ->select('MPROD', 'MQREQ', 'MRDTE')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '=', $fecha],
            ])
            ->sum('MQREQ');

        $RKMR = KMR::query()
            ->select('MQTY', 'MRDTE', 'MRCNO')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '=', $fecha],
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

        $RFMA = FMA::query()
            ->select('MRDTE', 'MQREQ')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '>=', $fecha],
                ['MRDTE', '<', $totalF],
            ])
            ->get()->toarray();
        $RKMR = KMR::query()
            ->select('MQTY', 'MRDTE')
            ->where([
                ['MPROD', '=', $producto],
                ['MRDTE', '>=', $fecha],
                ['MRDTE', '<', $totalF],
            ])
            ->get()->toarray();

        while ($connt < $dias) {
            $total = 0;
            $totalf = 0;
            $totalk = 0;
            $DMBMS = array_column($MBMS, 'LSDTE');
            $VMBMS = array_column($MBMS, 'TOTAL');
            $pos1 = array_search($dia, $DMBMS);
            if ($pos1 == false) {
            } else {
                $total =  $VMBMS[$pos1];
            }
            $DFMA = array_column($RFMA, 'MRDTE');
            $VFMA = array_column($RFMA, 'MQREQ');
            $pos2 = array_search($dia, $DFMA);
            if ($pos2 == false) {
            } else {
                $totalf = $VFMA[$pos2];
            }
            $DKMR = array_column($RKMR, 'MRDTE');
            $VKMR = array_column($RKMR, 'MQTY');
            $pos3 = array_search($dia, $DKMR);
            if ($pos3 == false) {
            } else {

                $totalk = $VKMR[$pos3];
            }
            $dia . '<br>';
            $total . '/' . $totalf . '/' . $totalk . '<br>';
            $tt = $total + $totalf + $totalk;
            $valTotal += [$dia => $tt];

            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
        }


        return $valTotal;
    }
}
