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
        $this->dias = $dias + 1;
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
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')->orderby('IPROD', 'DESC')
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

        $valPDp1  = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->whereraw("(FPROD='" .   $finaleskfp  . "')")
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
            ])
            ->get();
        $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" .  $finaleswrk  . "')")
            ->get()->toarray();
        $prowk = array_column($WCT, 'RPROD');
        $prowrok = array_column($WCT, 'RWRKC');
        // $prodcqa = array_column($cond, 'IPROD');
        // $minba = array_column($cond, 'IMIN');
        foreach ($cadfinal as $prod) {
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

            // $padre  += ['total' => $valPDp->where('FPROD', $prod)->sum('FQTY')];
            foreach ($valPDp1->where('FPROD', $prod) as $Fprod) {
                $forcastp  += [$Fprod['FTYPE'] . $Fprod['FRDTE'] . substr($Fprod['FPCNO'], 4, 1) => $Fprod['FQTY']];
            }
            $padre  += ['tPlan' =>  $valPDp1->where('FPROD', $prod)->sum('FQTY')];
            $pos = array_search($prod, $prowk);
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
