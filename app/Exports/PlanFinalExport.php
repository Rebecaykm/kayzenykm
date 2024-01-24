<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\KMR;
use App\Models\KFP;
use App\Models\FRT;
use App\Models\IIM;
use App\Models\ZCC;
use App\Models\LOGSUP;
use App\Models\FMA;
use App\Models\ECL;
use App\Models\MBM;
use App\Models\FSO;
use App\Models\YMCOM;
use App\Models\YK006;

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
        $this->dias = $dias+1;
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
        $prods = IIM::query()
        ->select('IPROD', 'IREF04')
        ->wherein('IREF04 ', $array)
        ->where([
            ['IID', '!=', 'IZ'],
            ['IMPLC', '!=', 'OBSOLETE'],
        ])
        ->where([
            ['IPROD', 'Not like', '%-SOR%'],
            ['IPROD', 'Not like', '%-830%']]
        )

        ->where('ICLAS', 'F1')
        ->distinct('IPROD')
        ->get()->toArray();



        $totalpa = array();
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
        $finaArra = array_column($prods, 'IPROD');
        $finales = implode("' OR  MPROD='",   $finaArra);
        $finaleskfp = implode("' OR  FPROD='",   $finaArra);
        $finaleswrk = implode("' OR  RPROD='",   $finaArra);
        $cadfinal = [];
        foreach ($prods as $prod) {
            // $contsub = self::contcargar($prod['IPROD']);
            // if ($contsub != 0) {
            array_push($cadfinal, $prod['IPROD']);
            // }
        }
        $finales = implode("' OR  MPROD='",  $cadfinal);
        $valfinales = KMR::query() //forecast
            ->select('MPROD', 'MRDTE', 'MQTY', 'MRCNO')
            ->where('MRDTE', '>=', $hoy)
            ->where('MRDTE', '<',  $totalF)
            ->whereraw("(MPROD='" . $finales  . "')")
            ->get()->toarray();

        $valPDp  = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get()->toarray();



        $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" .  $finaleswrk  . "')")
            ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');
        $prowrok = array_column($WCT, 'RWRKC');
        // $prodcqa = array_column($cond, 'IPROD');
        // $minba = array_column($cond, 'IMIN');

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
            $pos = array_search($prod, $prowk );

            $padre  += ['Wrc' => $prowrok[$pos]];

            $padre  += $forcastp;
            $padre  +=  $planpadre;
            $inF1 += ['padre' =>  $padre];

            $sepa = [];
            $datossub = [];



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
}
