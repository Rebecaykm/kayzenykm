<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\KMR;
use App\Models\kFP;
use App\Models\frt;
use App\Models\Iim;
use App\Models\ZCC;
use App\Models\LOGSUP;
use App\Models\Fma;
use App\Models\YMCOM;
use App\Models\Ecl;
use App\Models\MBMr;
use App\Models\Fso;
use App\Models\YK006;
use App\Models\MStructure;
use Illuminate\Contracts\View\View;

class PlansubExport implements FromView
{
    private $id; // declaras la propiedad
    private $fecha;
    private $dias;
    private $TP;
    public function __construct($fecha, $dias, $tp)
    {
        // $this->id = $id;
        $this->fecha = $fecha;
        $this->dias = $dias;
        $this->TP = $tp;
    }
    public function view(): View
    {

        $dias = $this->dias;
        $fecha = $this->fecha;
        $hoy = $this->fecha;
        $TP = $this->TP;
        $datos = [];
        $array = explode(",", $TP);
        $plan1 = Iim::query()
            ->select('IPROD', 'IREF04')
            ->wherein('IREF04 ', $array)
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('IPROD', 'Not like', '%-830%')
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();
        $total = 0;
        $datos = self::CargarforcastF1($plan1, $fecha, $dias);


        $partsrev = array_column($plan1, 'IPROD');
        $cadepar = implode("' OR  IPROD='",      $partsrev);

        return view('planeacion.RepSubfinal', [
            'general' => $general
        ]);
    }
    function CargarforcastF1($prods, $hoy, $dias)
    {
        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $finaArra = array_column($prods, 'IPROD');
        $finales = implode("' OR  MPROD='",   $finaArra);
        $finaleskfp = implode("' OR  FPROD='",   $finaArra);

        $valPDp  = kFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();
        foreach ($prods as $prod) {

            $inF1 = array();
            $padre = [];
            $dia = $hoy;
            $planpadre = [];
            $tfirme = 0;
            $forcastp = [];
            $padre  += ['parte' => $prod['IPROD']];

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

                    }
                }
            }

            $padre  += ['tfirme' => $tfirme];
            $padre  += $forcastp;
            $padre  +=  $planpadre;
            // dd( $padre);
            $inF1 += ['padre' =>  $padre];

            $datossub = self::Cargarforcast($prod['IPROD'], $hoy, $dias,  $forcastp);
            $datossub = [];
            $inF1 += ['hijos' =>  $datossub];
            array_push($totalpa, $inF1);
        }
        //             if($prod['IPROD']=="DGH934300A                         ")
        //             {
        // // dd($inF1 );
        //             }


        return   $totalpa;
    }
    function Cargarforcast($prod1, $hoy, $dias, $valDp)
    {
        //  $Sub = self::cargar($prod1);
        $Sub = YMCOM::query()
            ->join('LX834F01.IIM', 'MCCPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereraw("(MCFPRO='" .  $prod1 . "') AND  (MCCCLS='M2' or  MCCCLS='M3' or  MCCCLS='M4')")
            ->where([['MCFPRO', 'not like', '%-830%'], ['MCFPRO', 'not like', '%-SOR%']])
            ->get()->toarray();


        $total = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $sub1 = array_column($Sub, 'MCCPRO');
        $cadsubsPlan = implode("' OR  FPROD='",  $sub1);
        $child = implode("' OR  MCCPRO='",  $sub1);
        $cadsubKMR = implode("' OR  MPROD='",  $sub1);
        $cadsubswrk = implode("' OR  RPROD='",  $sub1);
        $Qa = implode("' OR  IPROD='",  $sub1);

        $KMRFINAL = YMCOM::query()
            ->join('LX834F01.IIM', 'MCCPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereraw("(MCCPRO='" .   $child  . "') AND ( MCFCLS='F1')")
            ->where('MCFPRO', 'not like', '%-830%')
            ->get()->toarray();

        $FINALLIST = array_column($KMRFINAL, 'MCFPRO');
        $FINALMCPRO = array_column($KMRFINAL, 'MCCPRO');
        $FINALCALS = array_column($KMRFINAL, 'MCFCLS');
        $FINALKMR = implode("' OR  MPROD='", $FINALLIST);

        // $RKMRfinal = KMR::query()
        //     ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
        //     ->whereraw("(MPROD='" .   $FINALKMR . "')")
        //     ->where([
        //         ['MRDTE', '>=', $hoy],
        //         ['MRDTE', '<', $totalF],
        //         ['MTYPE', '=', 'F'],
        //     ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
        //     ->get()->toarray();


        // ------------------------------------------------------------------------------------pADRES
        $KMRPARENT = YMCOM::query()
            ->join('LX834F01.IIM', 'MCFPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS', 'IID', 'IMPLC')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereraw("(MCCPRO='" .   $child  . "') AND (MCFCLS='M2' or  MCFCLS='M3' or  MCFCLS='M4') AND (IID != 'IZ' AND IMPLC != 'OBSOLETE') ")
            ->get()->toarray();
        $kmrmccprod = array_column($KMRPARENT, 'MCCPRO');
        $kmrmcfprod = array_column($KMRPARENT, 'MCFPRO');
        $KMRMCFCLS = array_column($KMRPARENT, 'MCFCLS');
        $PADREKMR = implode("' OR  MPROD='", $kmrmcfprod);

        // $RKMR = KMR::query()
        //     ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
        //     ->whereraw("(MPROD='" .   $PADREKMR . "')")
        //     ->where([
        //         ['MRDTE', '>=', $hoy],
        //         ['MRDTE', '<', $totalF],
        // ['MTYPE', '=', 'F'],
        //     ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
        //     ->get()->toarray();







        // -----------------------------------------FIRME PLAN
        $valPD = kFP::query()
            ->select('FPROD', 'FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->whereraw("(FPROD='" .  $cadsubsPlan . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();


        // $VALRKMR = KMR::query()
        //     ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
        //     ->whereraw("(MPROD='" .   $cadsubKMR . "')")
        //     ->where([
        //         ['MRDTE', '>=', $hoy],
        //         ['MRDTE', '<', $totalF],
        //     ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
        //     ->get()->toarray();






        // $kmrprod = array_column($RKMRfinal, 'MPROD');
        // $kmrmtype = array_column($RKMRfinal, 'MRCNO');
        // $KMRfecha = array_column($RKMRfinal, 'MRDTE');
        // $KMRMtotal = array_column($RKMRfinal, 'TOTAL');

        // $kmrpad = array_column($RKMR, 'MPROD');
        // $kmrpadno = array_column($RKMR, 'MRCNO');
        // $KMRpaddat = array_column($RKMR, 'MRDTE');
        // $KMRmtoalpa = array_column($RKMR, 'TOTAL');


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


        // $RFMA = FMA::query()
        //     ->selectRaw('MPROD,MRDTE, SUM(MQREQ) as Total')
        //     ->whereraw("(MPROD='" .  $cadsubKMR  . "')")
        //     ->where([
        //         ['MRDTE', '>=', $hoy],
        //         ['MRDTE', '<', $totalF],
        //     ])
        //     ->groupBy('MPROD', 'MRDTE')
        //     ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');
        $prowrok = array_column($WCT, 'RWRKC');

        $prodcqa = array_column($cond, 'IPROD');
        $pqa = array_column($cond, 'IMBOXQ');
        $minba = array_column($cond, 'IMIN');
        $sepa = [];

        foreach ($sub1 as $subs) {


            $padreskmr = [];
            $finaleskmr = [];
            $numpar = [];
            $numpaplan =  [];
            $total = 0;
            // while (($key5 = array_search($subs,  $FINALMCPRO)) !== false) {

            //     array_push($finaleskmr, $FINALLIST[$key5]);

            //     unset($FINALLIST[$key5]);
            //     unset($FINALMCPRO[$key5]);
            //     unset($FINALCALS[$key5]);
            // }
            // while (($key2 = array_search($subs,  $kmrmccprod)) !== false) {
            //     if ($kmrmcfprod[$key2] != $subs) {
            //         array_push($padreskmr, $kmrmcfprod[$key2]);
            //     }
            //     unset($kmrmccprod[$key2]);
            //     unset($KMRMCFCLS[$key2]);
            //     unset($kmrmcfprod[$key2]);
            // }
            // $FINALLIST = array_column($KMRFINAL, 'MCFPRO');
            // $FINALMCPRO = array_column($KMRFINAL, 'MCCPRO');
            // $FINALCALS = array_column($KMRFINAL, 'MCFCLS');
            // $FINALKMR = implode("' OR  MPROD='", $FINALLIST);

            $contpadres = count($padreskmr);
            $contF1 = count($finaleskmr);

            if ($contF1 >= 1) {

                $texfinal = implode(',' . '<br> ',    $finaleskmr);

                $cadfinal = implode("' OR  MPROD='",     $finaleskmr);
                // $cadsubsL = implode("' OR  LPROD='", $padreskmr );
            } else {
                $texfinal = $finaleskmr[0] ?? '';
                // $cadsubsL = $$padreskmr[0];
                $cadfinal = $finaleskmr[0] ?? '';
            }
            if ($contpadres >= 1) {

                $texpadre = implode(',' . '<br> ',   $padreskmr);

                $cadfinal = implode("' OR  MPROD='",   $padreskmr);
                // $cadsubsL = implode("' OR  LPROD='", $padreskmr );
            } else {
                $texpadre = $padreskmr[0] ?? '';
                // $cadsubsL = $$padreskmr[0];
                $texpadre = $padreskmr[0] ?? '';
            }
            $forcast = [];
            $Tshop = 0;
            $Tplan = 0;
            $Tfirme = 0;
            $total = 0;
            // ------------------------------- sacar valores KMR

            // foreach ($padreskmr as $P1) {
            //     $kmrpad = array_column($RKMR, 'MPROD');
            //     $kmrpadno = array_column($RKMR, 'MRCNO');
            //     $KMRpaddat = array_column($RKMR, 'MRDTE');
            //     $KMRmtoalpa = array_column($RKMR, 'TOTAL');
            //     while (($key3 = array_search($P1,      $kmrpad)) !== false) {
            //         $dia = $KMRpaddat[$key3];
            //         $turno =    $kmrpadno[$key3];
            //         $total =    $KMRmtoalpa[$key3] + 0;
            //         $valt = substr($turno, 4, 1);
            //         if (array_key_exists('KMRS' . $dia . $valt,  $forcast) !== false) {
            //             $total = $forcast['KMRS' . $dia . $valt] + $total;
            //             $forcast['KMRS' . $dia . $valt] = $total;
            //         } else {
            //             $forcast  += ['KMRS' . $dia . $valt => $total];
            //         }

            //         unset($kmrpad[$key3]);
            //         unset($kmrpadno[$key3]);
            //         unset($KMRpaddat[$key3]);
            //         unset($KMRmtoalpa[$key3]);
            //     }
            // }


            $total = 0;


            // foreach ($finaleskmr as $F1) {

            //     while (($key3 = array_search($F1,   $kmrprod)) !== false) {
            //         $dia = $KMRfecha[$key3];
            //         $turno =  $kmrmtype[$key3];
            //         $total =   $KMRMtotal[$key3] + 0;
            //         $valt = substr($turno, 4, 1);
            //         if (array_key_exists('kmr' . $dia . $valt,  $forcast) !== false) {
            //             $total = $forcast['kmr' . $dia . $valt] + $total;
            //             $forcast['kmr' . $dia . $valt] = $total;
            //         } else {
            //             $forcast  += ['kmr' . $dia . $valt => $total];
            //         }

            //         unset($kmrprod[$key3]);
            //         unset($kmrmtype[$key3]);
            //         unset($KMRfecha[$key3]);
            //         unset($KMRMtotal[$key3]);
            //     }
            // }
            // $kmrprod = array_column($RKMRfinal, 'MPROD');
            // $kmrmtype = array_column($RKMRfinal, 'MRCNO');
            // $KMRfecha = array_column($RKMRfinal, 'MRDTE');
            // $KMRMtotal = array_column($RKMRfinal, 'TOTAL');
            //             if($subs=="DGH934310A -AP                     ")
            //             {
            // dd( $subs,$finaleskmr,$kmrprod,$RKMRfinal);
            //             }
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
            // foreach ($VALRKMR as $reg10) {
            //     if ($reg10['MPROD'] == $subs) {
            //         $dia =  $reg10['MRDTE'];
            //         $turno =  $reg10['MRCNO'];
            //         $total =  $reg10['TOTAL'] + 0;
            //         $valt = substr($turno, 4, 1);
            //         $numpaplan += ['KMRS' . $dia . $valt => $total];
            //         $Tshopkmr =   $Tshopkmr + $total;
            //     }
            // }
            $pos = array_search($subs, $prodcqa);
            $poskwr = array_search($subs,   $prowk);

            $numpar += ['sub' => $subs, 'plan' => $numpaplan,  'padres' => $texfinal, 'forcast' => $forcast, 'Qty' => $pqa[$pos] ?? 0, 'minbal' => $minba[$pos] ?? 0, 'wrk' => $prowrok[$poskwr] ?? 0, 'Tshop' => $Tshop, 'Tplan' => $Tplan, 'Tfirme' => $Tfirme, 'KMRpadres'  =>  $texpadre ?? 0, 'Totalpadres' => $Tshopkmr];
            $sepa += [$subs => $numpar];
        }

        return    $sepa;
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
    function contcargar($prod)
    {
        $res = MStructure::query()
            ->select('Final')
            ->where('Final', $prod)
            ->where('clase', '!=', '01')
            ->count();
        return $res;
    }
}
