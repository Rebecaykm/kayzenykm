<?php

use App\Models\LWK;
use App\Models\IPB;
use App\Models\Kmr;
use App\Models\Fma;
use App\Models\Ecl;
use App\Models\kFP;
use App\Models\Fso;
use App\Models\Fpo;
use App\Models\MBMr;
use App\Models\ZCC;
use App\Models\Structure;
use Illuminate\Support\Arr;

date_default_timezone_set('America/Monterrey');
// include_once("conexionloc.php");

class registros
{


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
            ->get();
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
            ->select('Final', 'componente')
            ->where('componente', $prod)
            ->distinct('Final')
            ->get();
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
        while ($connt <= $dias) {
            $inF1 = [
                'Dia' => $dia,
            ];
            $tD = 0;
            $tN = 0;
            $valD = self::Forecast($prod, $dia, '%D%');
            $valPD = self::plan($prod, $dia, '%D%');
            $valFD = self::Firme($prod, $dia, '%D%');
            $valN = self::Forecast($prod, $dia, '%N%');
            $valPN = self::plan($prod, $dia, '%N%');
            $valFN = self::Firme($prod, $dia, '%N%');
            $valSD = self::ShopO($prod, $dia, '%D%');
            $valSN = self::ShopO($prod, $dia, '%N%');

            $inF1 += ['F' . $dia . 'D' => $valD];
            $inF1 += ['F' . $dia . 'N' => $valN];
            $inF1 += ['P' . $dia . 'D' => $valPD];
            $inF1 += ['P' . $dia . 'N' => $valPN];
            $inF1 += ['Fi' . $dia . 'D' => $valFD];
            $inF1 += ['Fi' . $dia . 'N' => $valFN];
            $inF1 += ['S' . $dia . 'D' => $valSD];
            $inF1 += ['S' . $dia . 'N' => $valSN];

            $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
            array_push($total, $inF1);
        }


        return $total;
    }

    function Cargarforcast($prod, $hoy, $dias)
    {
        $inF1 = array();
        $total = array();
        $connt = 1;
        $dia = $hoy;
        while ($connt <= $dias) {
            $inF1 = [
                'sub' => $hoy,
            ];

            $contF1 = self::contcargarF1($prod);
            $tD = 0;
            $tN = 0;
            $tPD = 0;
            $tFD = 0;
            $tPN = 0;
            $tFN = 0;
            $tSD = 0;
            $tSN = 0;
            $reN = 0;
            $reD = 0;
            if ($contF1 != 0) {
                $F1 = self::cargarF1($prod);
                foreach ($F1 as $F1s) {
                    $valD = self::Forecast($F1s->Final, $hoy, '%D%');
                    $valN = self::Forecast($F1s->Final, $hoy, '%N%');
                    $tD = $valD + $tD;
                    $tN = $valN + $tN;
                }

                $valPD = self::plan($prod, $dia, '%D%');
                $valFD = self::Firme($prod, $dia, '%D%');
                $valPN = self::plan($prod, $dia, '%N%');
                $valFN = self::Firme($prod, $dia, '%N%');
                $valSD = self::ShopO($prod, $dia, '%D%');
                $valSN = self::ShopO($prod, $dia, '%N%');
                $valD =  $valD + 0;
                $valPD =  $valPD + 0;
                $valFD = $valFD + 0;
                $valN =  $valN + 0;
                $valPN = $valPN + 0;
                $valFN = $valFN  + 0;
                $valSD = $valSD  + 0;
                $valSN = $valSN + 0;


                $inF1 += ['F' . $dia . 'D' => $valD];
                $inF1 += ['F' . $dia . 'N' => $valN];
                $inF1 += ['P' . $dia . 'D' => $valPD];
                $inF1 += ['P' . $dia . 'N' => $valPN];
                $inF1 += ['Fi' . $dia . 'D' => $valFD];
                $inF1 += ['Fi' . $dia . 'N' => $valFN];
                $inF1 += ['S' . $dia . 'D' => $valSD];
                $inF1 += ['S' . $dia . 'N' => $valSN];
            } else {

                $valD = self::Forecast($prod, $dia, '%D%');
                $valPD = self::plan($prod, $dia, '%D%');
                $valFD = self::Firme($prod, $dia, '%D%');
                $valN = self::Forecast($prod, $dia, '%N%');
                $valPN = self::plan($prod, $dia, '%N%');
                $valFN = self::Firme($prod, $dia, '%N%');
                $valSD = self::ShopO($prod, $dia, '%D%');
                $valSN = self::ShopO($prod, $dia, '%N%');

                $valD =  $valD + 0;
                $valPD =  $valPD + 0;
                $valFD = $valFD + 0;
                $valN =  $valN + 0;
                $valPN = $valPN + 0;
                $valFN = $valFN  + 0;
                $valSD = $valSD  + 0;
                $valSN = $valSN + 0;

                $inF1 += ['F' . $dia . 'D' => $valD];
                $inF1 += ['F' . $dia . 'N' => $valN];
                $inF1 += ['P' . $dia . 'D' => $valPD];
                $inF1 += ['P' . $dia . 'N' => $valPN];
                $inF1 += ['Fi' . $dia . 'D' => $valFD];
                $inF1 += ['Fi' . $dia . 'N' => $valFN];
                $inF1 += ['S' . $dia . 'D' => $valSD];
                $inF1 += ['S' . $dia . 'N' => $valSN];
            }
            $dia = $dia = date('Ymd', strtotime($dia . '+1 day'));
            $connt++;
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
            ->select('BPROD', 'BCLAS', 'BCHLD', 'BCLAC', 'BDDIS')
            ->where('BPROD', '=', $prod)
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

        $MBMS = ECL::query()
            ->select('LPROD', 'LQORD', 'LSDTE', 'CLCNO')
            ->where('LPROD', '=', $producto)
            ->where('LSDTE', '=', $fecha)
            ->where('CLCNO', 'Like', $turno)
            ->count();

        $ECL = 0;
        if ($MBMS == 0) {
            $ECL = 0;
        } else {
            $MBMS = ECL::query()
                ->select('LPROD', 'LQORD', 'LSDTE', 'CLCNO')
                ->where('LPROD', '=', $producto)
                ->where('LSDTE', '=', $fecha)
                ->where('CLCNO', 'Like', $turno)
                ->sum('LQORD');
        }



        return $ECL;
    }
}
