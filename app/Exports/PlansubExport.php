<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\KMR;
use App\Models\KFP;
use App\Models\FRT;
use App\Models\LIM;
use App\Models\ZCC;
use App\Models\LOGSUP;
use App\Models\FMA;
use App\Models\YMCOM;
use App\Models\ECL;
use App\Models\MBMr;
use App\Models\FSO;
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
        $plan1 = LIM::query()
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
            ->select('MCCPRO', 'MCFPRO', 'MCFCLS')
            ->whereraw("(MCFPRO='" . $finales . "') AND  (MCCCLS='M2' or  MCCCLS='M3' or  MCCCLS='M4' or  MCCCLS='F1')")
            ->where([['MCFPRO', 'not like', '%-830%'], ['MCFPRO', 'not like', '%-SOR%']])
            ->get()->toarray();
            $finaleswrk = implode("' OR  RPROD='",   array_column($Sub, 'MCCPRO'));

            $WCT = FRT::query()
            ->select('RWRKC', 'RPROD')
            ->whereraw("(RPROD='" .   $finaleswrk  . "')")
            ->get()->toarray();
            $prowk = array_column($WCT, 'RPROD');
            $wk = array_column($WCT, 'RWRKC');

        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));

        $valPDp  = KFP::query() //plan
            ->select('FRDTE', 'FQTY', 'FPCNO', 'FTYPE', 'FPROD')

            ->wherein('FPROD', array_column($Sub, 'MCCPRO'))
            ->where([
                ['FRDTE', '>=', $hoy],
                ['FRDTE', '<', $totalF],
                ['FTYPE', '=', 'F'],
            ])
            ->orderby('FPROD', 'DESC')
            ->get()->toarray();

        $YMCOM = array_column($Sub, 'MCFPRO');
        $YMCOMCH = array_column($Sub, 'MCCPRO');

        $kfppadre = array_column($valPDp, 'FPROD');

        $kfdte = array_column($valPDp, 'FRDTE');
        $kfqty = array_column($valPDp, 'FQTY');
        $kfturno = array_column($valPDp, 'FPCNO');

        foreach ($plan1 as $prod) {
            $padre = [];
            $datos = [];
            $hijos = [];

            $Fore = [];

            while (($key6 = array_search($prod['IPROD'], $kfppadre)) !== false) {
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
            $padre += ['parte' => $prod['IPROD']];
            $padre += ['fore' =>  $Fore];
            $datos += ['padre' => $padre];
            while (($key5 = array_search($prod['IPROD'], $YMCOM)) !== false) {
                $hijo = [];
                if ($YMCOMCH[$key5] == $prod['IPROD']) {
                } else {

                    $Forehijo = [];
                    $poskwr = array_search($YMCOMCH[$key5],   $prowk);
                    $hijo += ['parte' => $YMCOMCH[$key5],'WKC'=>$wk[ $poskwr]];

                    while (($key6 = array_search($YMCOMCH[$key5], $kfppadre)) !== false) {
                        $kfppadre[$key6];
                        $kfdte[$key6];
                        $kfqty[$key6];
                        $kfturno[$key6];
                        $valt = substr($kfturno[$key6], 4, 1);
                        $Forehijo  += ['F' . $kfdte[$key6] . $valt => $kfqty[$key6]];
                        unset($kfppadre[$key6]);
                        unset($kfdte[$key6]);
                        unset($kfturno[$key6]);
                    }

                $hijo += ['Forehijo' => $Forehijo];
                }
                unset($YMCOM[$key5]);
                unset($YMCOMCH[$key5]);
                if (count($hijo) != 0) {
                    array_push($hijos, $hijo);
                }




            }
            $datos += ["hijos" => $hijos];
            array_push($general, $datos);

        }


        // $datos = self::CargarforcastF1($plan1, $fecha, $dias);

        $reporte += ['res' => $general, 'fecha' => $fecha, 'dias' => $dias];

        $partsrev = array_column($plan1, 'IPROD');
        $cadepar = implode("' OR  IPROD='",      $partsrev);

        return view('planeacion.RepSubfinal', [
            'general' => $reporte
        ]);
    }
}
