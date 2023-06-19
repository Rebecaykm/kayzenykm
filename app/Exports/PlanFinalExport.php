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
use App\Models\Ecl;
use App\Models\MBMr;
use App\Models\Fso;
use App\Models\YK006;
use App\Models\MStructure;
use Illuminate\Contracts\View\View;

class PlanFinalExport implements FromView
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
        $prods = Iim::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $TP . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('IPROD', 'Not like', '%-SOR%')
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')
            ->get()->toArray();
        // $datos = self::CargarforcastF1Report($plan1, $fecha, $dias);



        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $finaArra = array_column($prods, 'IPROD');
        $finales = implode("' OR  MPROD='",   $finaArra);
        $finaleskfp = implode("' OR  FPROD='",   $finaArra);
        $cadfinal = [];
        foreach ($prods as $prod) {
            $contsub = self::contcargar($prod['IPROD']);
            if ($contsub != 0) {
                array_push($cadfinal, $prod['IPROD']);
            }
        }
        $finales = implode("' OR  MPROD='",  $cadfinal);
        $valfinales = kmr::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $hoy)
            ->where('MRDTE', '<',  $totalF)
            ->whereraw("(MPROD='" . $finales  . "')")
            ->get()->toarray();

        $valPDp  = kFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();

        $subcom = implode("' OR  Final='",   $cadfinal);
        $res = MStructure::query()
            ->select('Final', 'Componente', 'Activo', 'Clase')
            ->whereraw("(Final='" . $subcom    . "')")
            // ->where('Final', $prod)
            ->where([
                ['clase', '!=', '01'],
                ['clase', '!=', 'F1'],
                ['Activo', '1'],
            ])
            ->get()->toArray();

        $finalres =    array_column($res, 'Final');
        $subcompo = array_column($res, 'Componente');
        $cadsubsPlan = implode("' OR  FPROD='",  $subcompo);
        $valPD = kFP::query()
            ->select('FPROD', 'FRDTE', 'FQTY', 'FPCNO', 'FTYPE')
            ->whereraw("(FPROD='" .  $cadsubsPlan . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();


        $cadsubssh = implode("' OR  SPROD='",  $subcompo);
        $valSD = Fso::query()
            ->select('SPROD', 'SDDTE', 'SQREQ', 'SOCNO')
            ->whereraw("(SPROD='" .  $cadsubssh  . "')")
            ->where('SDDTE', '>=', $hoy)
            ->where('SDDTE', '<', $totalF)
            ->get()->toarray();

        $cadsubswrk = implode("' OR  RPROD='", $subcompo);
        $Qa = implode("' OR  IPROD='",  $subcompo);


        $cond = IIM::query()
            ->select('ICLAS', 'IMBOXQ', 'IMPLC', 'IPROD', 'IMIN')
            ->whereraw("(IPROD='" . $Qa  . "')")
            ->get()->toArray();

        $WCT = Frt::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" .  $cadsubswrk  . "')")
            ->get()->toarray();

        $prowk = array_column($WCT, 'RPROD');
        $prowrok = array_column($WCT, 'RWRKC');
        $prodcqa = array_column($cond, 'IPROD');
        $pqa = array_column($cond, 'IMBOXQ');
        $minba = array_column($cond, 'IMIN');

        foreach ($cadfinal as $prod) {
            $Tshop = 0;
            $Tplan = 0;
            $Tfirme = 0;
            $inF1 = array();
            $padre = [];
            $dia = $hoy;
            $connt = 1;
            $i = 0;
            $planpadre = [];
            $totalP = 0;
            $tPlan = 0;
            $forcastp = [];
            $padre  += ['parte' => $prod];
            $numpar = [];
            $numpaplan =  [];

            if (count($valfinales) > 0) {
                $resreg4 = array_column($valfinales, 'MPROD');
                $MRDTE = array_column($valfinales, 'MRDTE');
                $MRCNO = array_column($valfinales, 'MRCNO');
                $MQTY = array_column($valfinales, 'MQTY');

                while (($key = array_search($prod, $resreg4)) != false) {
                    // echo "<script>console.log('Console: " .  $key . '/' . $prod . "' );</script>";
                    $dia =  $MRDTE[$key];
                    $turno =  $MRCNO[$key];
                    $total =  $MQTY[$key] + 0;
                    $valt = substr($turno, 4, 1);
                    $forcastp  += ['For' . $dia . $valt => $total];
                    $totalP = $totalP + $total;
                    unset($resreg4[$key]);
                }
            }

            $padre  += ['total' => $totalP];


            if (count($valPDp) > 0) {
                $resreg6 = array_column($valPDp, 'FPROD');
                $FRDTE = array_column($valPDp, 'FRDTE');
                $FRCNO = array_column($valPDp, 'FPCNO');
                $FQTY = array_column($valPDp, 'FQTY');
                $FTYPE = array_column($valPDp, 'FTYPE');
                while (($key2 = array_search($prod, $resreg6)) != false) {
                    $dia = $FRDTE[$key2];
                    $turno =  $FRCNO[$key2];
                    $tipo = $FTYPE[$key2];
                    $total =  $FQTY[$key2] + 0;
                    $valt = substr($turno, 4, 1);
                    $planpadre += [$tipo . $dia . $valt => $total];
                    $tPlan = $tPlan + $total;
                    unset($resreg6[$key2]);
                }
            }
            $padre  += ['tPlan' => $tPlan];
            $padre  += $forcastp;
            $padre  +=  $planpadre;
            $inF1 += ['padre' =>  $padre];
            $prowk = array_column($WCT, 'RPROD');
            $prowrok = array_column($WCT, 'RWRKC');
            $prodcqa = array_column($cond, 'IPROD');
            $pqa = array_column($cond, 'IMBOXQ');
            $minba = array_column($cond, 'IMIN');
            $sepa = [];
            $datossub = [];


            while (($key = array_search($prod, $finalres)) != false) {

                $contF1 = self::contcargarF1($subcompo[$key]);
                $pos = array_search($prod, $pqa);
                $poskwr = array_search($prod,  $prowrok);
                if ($contF1 > 1) {
                    $F1 = self::cargarF1($subcompo[$key]);
                    $padres1 = array_column($F1, 'final');
                    $texpadre = implode(',' . ' <br> ', $padres1);
                    $cadsubs = implode("' OR  MPROD='",  $padres1);
                    $cadsubsL = implode("' OR  LPROD='",  $padres1);
                } else {
                    $cadsubs = $prod;
                    $cadsubsL =  $prod;
                    $texpadre =  $prod;
                }
                foreach ($valPD as $reg3) {
                    if ($reg3['FPROD'] == $subcompo[$key]) {
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
                foreach ($valSD as $reg4) {
                    if ($reg4['SPROD'] == $subcompo[$key]) {
                        $dia =  $reg4['SDDTE'];
                        $turno =  $reg4['SOCNO'];
                        $total =  $reg4['SQREQ'] + 0;
                        $valt = substr($turno, 4, 1);
                        $numpaplan += ['S' . $dia . $valt => $total];
                        $Tshop =   $Tshop + $total;
                    }
                }
                // $MBMS = ECL::query()
                //     ->selectRaw('LSDTE, SUM(LQORD) as Total,CLCNO,LPROD ')
                //     ->whereraw("(LPROD='" .  $cadsubsL . "')")
                //     ->where([
                //         ['LSDTE', '>=', $hoy],
                //         ['LSDTE', '<=', $totalF],
                //     ])
                //     ->groupBy('LPROD', 'LSDTE', 'CLCNO')
                //     ->get()->toarray();
                // $RFMA = FMA::query()
                //     ->selectRaw('MPROD,MRDTE, SUM(MQREQ) as Total')
                //     ->whereraw("(MPROD='" .  $cadsubs . "')")
                //     ->where([
                //         ['MRDTE', '>=', $hoy],
                //         ['MRDTE', '<=', $totalF],
                //     ])
                //     ->groupBy('MPROD', 'MRDTE')
                //     ->get()->toarray();

                // $RKMR = KMR::query()
                //     ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD')
                //     ->whereraw("(MPROD='" .  $cadsubs . "')")
                //     ->where([
                //         ['MRDTE', '>=', $hoy],
                //         ['MRDTE', '<=', $totalF],
                //     ])->groupBy('MRDTE', 'MRCNO', 'MPROD')
                //     ->get()->toarray();
                $forcast = [];

                // if (count($RKMR) > 0) {
                //     foreach ($RKMR as $reg) {
                //         $dia =  $reg['MRDTE'];
                //         $turno =  $reg['MRCNO'];
                //         $total =  $reg['TOTAL'] + 0;
                //         $valt = substr($turno, 4, 1);
                //         $forcast  += ['kmr' . $dia . $valt => $total];
                //     }
                // }
                // if (count($MBMS) > 0) {
                //     foreach ($MBMS as $reg1) {


                //         $dia =  $reg1['LSDTE'];
                //         $turno =  $reg1['CLCNO'];
                //         $total =  $reg1['TOTAL'] + 0;
                //         $valt = substr($turno, 4, 1);
                //         $forcast  += ['ecl' . $dia . $valt => $total];
                //     }
                // }

                // if (count($RFMA) > 0) {
                //     foreach ($RFMA  as $reg2) {
                //         $dia =  $reg2['MRDTE'];
                //         $total =  $reg2['TOTAL'] + 0;
                //         $forcast += ['FMA' . $dia . 'D' => $total];
                //     }
                // }
                $numpar = [];

                $numpar += ['sub' => $subcompo[$key], 'plan' => $numpaplan,  'padres' => $texpadre, 'forcast' => $forcast, 'Qty' => $pqa[$pos], 'minbal' => $minba[$pos], 'wrk' => $prowrok[$poskwr], 'Tshop' => $Tshop, 'Tplan' => $Tplan, 'Tfirme' => $Tfirme];

                unset($finalres[$key]);
                $datossub +=  $numpar;
                $sepa += [$subcompo[$key] => $numpar];
            }
            $inF1 += ['hijos' =>   $sepa];
            array_push($totalpa, $inF1);
        }
        $general = [];

        $general += [
            'res' => $totalpa,
            'dias' => $dias,
            'fecha' => $fecha
        ];

        return view('planeacion.RepPlanfinal', [
            'general' => $general
        ]);
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