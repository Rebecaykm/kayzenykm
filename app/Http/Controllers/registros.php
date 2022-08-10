<?php

use App\Models\LWK;
use App\Models\IPB;
use App\Models\Kmr;


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

        $plan = kmr::query()
            ->select('MPROD', 'MQTY', 'MRDTE', 'MRCNO')
            ->where('MRDTE', '=', $fecha)
            ->where('MPROD', '=', $producto)
            ->where('MRCNO', 'like', $turno)
            ->count();

        return $plan;
    }


    function contar($producto, $fecha, $fechafin, $turno)
    {
        $WCs = kmr::query()
            ->select('MPROD', 'MQTY', 'MRDTE', 'MRCNO')
            ->where('MRDTE', '>=', $fecha)
            ->where('MRDTE', '<=', $fechafin)
            ->where('MPROD', '=', $producto)
            ->where('MRCNO', 'like', $turno)
            ->count();
        return $WCs;
    }

    function Productos()
    {
    }
}
