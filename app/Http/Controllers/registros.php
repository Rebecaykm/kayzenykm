<?php

use App\Models\LWK;
use App\Models\IPB;
use App\Models\Kmr;
use App\Models\MBMr;

date_default_timezone_set('America/Monterrey');
// include_once("conexionloc.php");

class registros
{

    function Forecast($producto, $fecha, $turno)
    {

       $plan = kmr::query()
            ->select('MPROD', 'MQTY', 'MRDTE', 'MRCNO')
            ->where('MRDTE', '=', $fecha)
            ->where('MPROD', '=', $producto)
            ->where('MRCNO', 'like', $turno)
            ->get();
        return $plan;
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
    function F1($pro)
    {
        $MBMS =MBMr::query()
        ->select('BPROD','BCLAS','BCHLD','BCLAC')
        ->where('BCHLD','=',$pro)
        ->get();
        return $MBMS;

    }

    function Productos()
    {
    }
}
