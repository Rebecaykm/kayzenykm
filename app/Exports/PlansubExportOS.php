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
use App\Models\YMCOM;
use App\Models\ECL;
use App\Models\MBM;
use App\Models\FSO;
use App\Models\YK006;

use Illuminate\Contracts\View\View;

class PlansubExportOS implements FromView
{
    private $id; // declaras la propiedad
    private $fecha;
    private $dias;
    private $TP;
    public function __construct($fecha, $dias, $tp)
    {
        $this->fecha = $fecha;
        $this->dias = $dias;
        $this->TP = $tp;
    }
    public function view(): View
    {

        $dias = $this->dias+1;
        $fecha = $this->fecha;
        $hoy = $this->fecha;
        $TP = $this->TP;
        $datos = [];
        $general = [];
        $reporte = [];
        $array = explode(",", $TP);
        $plan1 = IIM::query()
            ->select('IPROD', 'IREF04', 'ICLAS', 'IMBOXQ')
            ->wherein('IREF04 ', $array)
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where('ICLAS', 'F1')
            ->distinct('IPROD')->ORDERBY('IPROD', 'DESC')
            ->get();
        $clases = ['M2', 'M3', 'M4', 'F1'];
        $Sub = YMCOM::query()
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS', 'MCCCLS')
            ->wherein('MCFPRO', $plan1->pluck('IPROD'))
            ->wherein('MCCCLS', $clases)
            ->get();
            $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));


        $RKMRfinal = KMR::query()
            ->selectRaw('SUM(MQTY) as Total,MRDTE,MRCNO,MPROD,MTYPE')
            ->whereIN('MPROD', $Sub ->pluck('MCFPRO'))
            ->where([
                ['MRDTE', '>=', $hoy],
                ['MRDTE', '<', $totalF],
                ['MTYPE', '=', 'F'],
            ])->groupBy('MRDTE', 'MRCNO', 'MPROD', 'MTYPE')
            ->get();

        $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->wherein("RPROD", $Sub->pluck('MCCPRO'))
            ->get();

        $valPDp  = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')
            ->wherein('FPROD', $Sub->pluck('MCCPRO'))
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
                ['FTYPE', '=', 'F'],
            ])
            ->orderby('FPROD', 'DESC')
            ->distinct('FPROD')
            ->get();
        foreach ($plan1 as $prod) {
            $padre = [];
            $datos = [];
            $hijos = [];
            $Fore = [];
            foreach ($valPDp->where('FPROD', $prod['IPROD']) as $item) {
                $valt = substr($item['FPCNO'], 4, 1);
                $Fore += ['F' . $item['FRDTE'] . $valt => $item['FQTY']];
            }
            $padre += ['parte' => $prod['IPROD']];
            $padre += ['fore' =>  $Fore];
            $datos += ['padre' => $padre];

            foreach ($Sub->where('MCFPRO', $prod['IPROD']) as $hijo) {
                $Forehijo = [];
                $forcast=[];
                if ($hijo['MCCPRO'] != $prod['IPROD']) {
                    $Forehijo += ['parte'=> $hijo['MCCPRO']];
                     $forhijo = [];
                     //firme
                    foreach ($valPDp->where('FPROD', $hijo['MCCPRO']) as $valhijo) {
                        $valt = substr($valhijo['FPCNO'], 4, 1);
                        $forhijo += ['F' . $valhijo['FRDTE'] . $valt => $valhijo['FQTY']];
                    }

                    //forcast
                    $fihijo=$Sub->where('MCCPRO', $hijo['MCCPRO'] );
                    $kmrpadress = $RKMRfinal->wherein('MPROD', $fihijo->pluck('MCFPRO'));
                    if(count(    $kmrpadress)!=0)
                    {
                        dd(    $RKMRfinal->toarray(), $kmrpadress->toarray(),  $fihijo->pluck('MCFPRO'));
                    }
                    foreach ($kmrpadress as $kmrp) {

                        $turno = $kmrp['MRCNO'];
                        $valt = substr($turno, 4, 1);
                        $forcast  += ['KMRS' . $kmrp['MRDTE'] . $valt => $kmrp['TOTAL']];
                    }

                    $Forehijo += ['Forehijo' => $forhijo];
                    $Forehijo += ['Forcasthijo' => $forcast ];
                    array_push($hijos, $Forehijo);
                }
            }
            $datos += ["hijos" => $hijos];

            array_push($general, $datos);
        }

        $reporte += ['res' => $general, 'fecha' => $fecha, 'dias' => $dias];

        return view('planeacion.RepSubfinalOS', [
            'general' => $reporte
        ]);
    }
}
