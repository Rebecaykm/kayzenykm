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
        $general = [];
        $reporte = [];
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

        $finales = implode("' OR  MCFPRO='",   array_column($plan1, 'IPROD'));

        $Sub = YMCOM::query()
            ->join('LX834F01.IIM', 'MCFPRO', '=', 'IPROD')
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->whereraw("(MCFPRO='" . $finales . "') AND  (MCCCLS='M2' or  MCCCLS='M3' or  MCCCLS='M4' or  MCCCLS='F1')")
            ->where([['MCFPRO', 'not like', '%-830%'], ['MCFPRO', 'not like', '%-SOR%']])
            ->get()->toarray();

        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));

        $finaleskfp = implode("' OR  FPROD='",   array_column($Sub, 'MCCPRO'));
        $valPDp  = kFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE','FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
                ['FTYPE', '=', 'F'],
            ])
            ->get()->toarray();

            $YMCOM = array_column($Sub, 'MCFPRO');
        $YMCOMCH = array_column($Sub, 'MCCPRO');

        $kfppadre = array_column($valPDp, 'FPROD');

        $kfdte = array_column($valPDp, 'FRDTE');
        $kfqty = array_column($valPDp, 'FQTY');
        $kfturno = array_column($valPDp, 'FPCNO');

        foreach ($plan1 as $prod) {
            $datos=[];
            $padre=[];
            $hijos=[];


           while(($key5 = array_search($prod['IPROD'], $YMCOM)) !== false)
            {

                $hijo=[];
                if( $YMCOMCH[$key5]==$prod['IPROD'])
                {
                    $padre += ['parte' =>$YMCOMCH[$key5] ];
                    $Fore = [];
                    while (($key6 = array_search( $YMCOMCH[$key5 ], $kfppadre)) !== false) {
                        $kfppadre[$key6];
                        $kfdte[$key6];
                        $kfqty[$key6];
                        $kfturno[$key6];
                        $valt = substr($kfturno[$key6], 4, 1);
                        $Fore += ['F' . $kfdte[$key6] . $valt => $kfqty[$key6]];
                        unset($kfppadre[$key6]);
                        unset($kfdte[$key6]);
                        unset($kfturno[$key6]);
                    }
                    $padre+= ['fore' =>  $Fore];
                    $datos+=['padre'=>$padre];
                    unset($YMCOM[$key5]);
                    unset($YMCOMCH [$key5]);

                }else
                {

                    $Forehijo = [];
                    $hijo+=[$YMCOMCH[$key5]=>$YMCOMCH[$key5]];

                    while (($key6 = array_search( $YMCOMCH [$key5 ], $kfppadre)) !== false) {
                        $kfppadre[$key6];
                        $kfdte[$key6];
                        $kfqty[$key6];
                        $kfturno[$key6];
                        $valt = substr($kfturno[$key6], 4, 1);
                        $Forehijo  += ['F' . $kfdte[$key6] . $valt => $kfqty[$key6]];
                        unset($kfppadre[$key6]);
                        unset($kfdte[$key6]);
                        unset($kfturno[$key6]);
                        $hijo+=['Forehijo'=> $Forehijo ];
                    }


                    unset($YMCOM[$key5]);
                unset($YMCOMCH [$key5]);
                }
                if(count($hijo)!=0)
                {
                    array_push($hijos,$hijo);

                }


            }
            $datos+=["hijos"=>$hijos];
          array_push($general,$datos);

        }

        // $datos = self::CargarforcastF1($plan1, $fecha, $dias);

        $reporte += ['res' => $general, 'fecha' => $fecha, 'dias' => $dias];

        $partsrev = array_column($plan1, 'IPROD');
        $cadepar = implode("' OR  IPROD='",      $partsrev);

        return view('planeacion.RepSubfinal', [
            'general' => $reporte
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
            // $datossub = [];
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








        // -----------------------------------------FIRME PLAN
        $valPD = kFP::query()
            ->select('FPROD', 'FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->whereraw("(FPROD='" .  $cadsubsPlan . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();


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


            $total = 0;


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
