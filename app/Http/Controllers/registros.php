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

date_default_timezone_set('America/Monterrey');
// include_once("conexionloc.php");

class registros
{

    function Forecast($producto, $fecha, $turno)
    {

       $plan = kmr::query()
            ->select( 'MQTY')
            ->where('MRDTE', '=', $fecha)
            ->where('MPROD', '=', $producto)
            ->where('MRCNO', 'like', $turno)
            ->sum('MQTY');

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
    function padre($pro)
    {

        $MBMS =MBMr::query()
        ->select('BPROD','BCLAS','BCHLD','BCLAC','BDDIS')
        ->where('BCHLD','=',$pro)
        ->where('BDDIS','=','99999999')
        ->get();
        return $MBMS;

    }
    function padrecon($pro)
    {

        $MBMS =MBMr::query()
        ->select('BPROD','BCLAS','BCHLD','BCLAC','BDDIS')
        ->where('BCHLD','=',$pro)
        ->where('BDDIS','=','99999999')
        ->count();
        return $MBMS;

    }
    function F1($pro)
    {

        $MBMS =MBMr::query()
        ->select('BPROD','BCLAS','BCHLD','BCLAC','BDDIS' )
        ->where('BCHLD','=',$pro)
        ->where('BCLAS','=','F1')
        ->get();

        return $MBMS;

    }

    function contarF1($pro)
    {
        $MBMS =MBMr::query()
        ->select('BPROD','BCLAS','BCHLD','BCLAC')
        ->where('BCHLD','=',$pro)
        ->where('BCLAS','=','F1')
        ->where('BDDIS','=','99999999')
        ->count();
        return $MBMS;

    }
    function plan($pro,$fecha,$turno)
    {

        $kfps =kFP::query()
        ->select('FPROD','FQTY','FTYPE','FRDTE','FPCNO' )
        ->where('FPROD','=',$pro)
        ->where('FPCNO','like',$turno)
        ->where('FRDTE','=',$fecha)
        ->where('FTYPE','=','P')
        ->sum('FQTY');
        return $kfps;
    }

    function Firme($pro,$fecha,$turno)
    {
        $kfps =kFP::query()
        ->select('FPROD','FQTY','FTYPE','FRDTE','FPCNO' )
        ->where('FPROD','=',$pro)
        ->where('FPCNO','like',$turno)
        ->where('FRDTE','=',$fecha)
        ->where('FTYPE','=','F')
        ->sum('FQTY');
        return $kfps;
    }
    function ShopO($pro,$fecha,$turno)
    {
        $Fsos =Fso::query()
        ->select('SQREQ' )
        ->where('SPROD','=',$pro)
        ->where('SOCNO','like',$turno)
        ->where('SDDTE','=',$fecha)
        ->sum('SQREQ');
        return $Fsos;
    }
    function contarfirme($pro,$fecha,$fechafin)
    {
        $kfps =kFP::query()
        ->select('FPROD','FQTY','FTYPE','FRDTE','FPCNO' )
        ->where('FPROD','=',$pro)
        ->where('FRDTE','>=',$fecha)
        ->where('FRDTE','<=',$fechafin)
        ->where('FTYPE','=','F')
        ->count();
        return $kfps;
    }
    function contarplan($pro,$fecha,$fechafin)
    {
        $kfps =kFP::query()
        ->select('FPROD','FQTY','FTYPE','FRDTE','FPCNO' )
        ->where('FPROD','=',$pro)
        ->where('FRDTE','>=',$fecha)
        ->where('FRDTE','<=',$fechafin)
        ->where('FTYPE','=','P')
        ->count();
        return $kfps;
    }

    function contarShopO($pro,$fecha,$fechafin)
    {
        $Fsos =Fso::query()
        ->select('SQREQ' )
        ->where('SPROD','=',$pro)
        ->where('SDDTE','>=',$fecha)
        ->where('SDDTE','<=',$fechafin)
        ->count();
        return $Fsos;
    }

    function requerimiento( $producto, $fecha, $turno)
    {

        $MBMS =ECL::query()
        ->select('LPROD', 'LQORD' ,'LSDTE','CLCNO')
        ->where('LPROD','=',$producto)
        ->where('LSDTE','=',$fecha)
        ->where('CLCNO','Like',$turno)
        ->count();

        $ECL=0;
    if($MBMS==0){
        $ECL=0;
    }else{
        $MBMS =ECL::query()
        ->select('LPROD', 'LQORD' ,'LSDTE','CLCNO')
        ->where('LPROD','=',$producto)
        ->where('LSDTE','=',$fecha)
        ->where('CLCNO','Like',$turno)
        ->get();
        dd($MBMS);
        foreach( $MBMS as $MBMSs){
            $ECL=$ECL+$MBMSs->LQORD;
            dd($ECL);
        }
    }



    return $ECL;
    }

}
