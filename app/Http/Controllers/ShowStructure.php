<?php

namespace App\Http\Controllers;

use App\Models\Iim;
use Illuminate\Http\Request;
use App\Models\IPB;
use App\Models\ZCC;
use App\Models\MStructure;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class ShowStructure extends Controller
{



    public function index(Request $request)
    {
        $Pr =  $request->SeProject ?? '*';

        $WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();


        $plan = Iim::query()
            ->select('IPROD', 'ICLAS', 'IREF04', 'IID', 'IMPLC', 'IBUYC', 'IMPLC')
            ->where([
                ['IREF04', 'like', '%' . $Pr . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
                ['IPROD', 'NOT LIKE', '%-830%'],
            ])
            ->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->distinct('IPROD')
            ->get();
        $PCs = ZCC::query()
            ->select('CCDESC')
            ->where([['CCID', '=', 'CC'], ['CCTABL', '=', 'SIRF4'], ['CCCODE', '=', $Pr]])
            ->first();
        $nombre = $PCs->CCDESC ?? '';
        $tot = [];
        if ($Pr != '*') {
            $fin = [];
            $BOM = [];
            foreach ($plan as $plans) {
                $cF1 = [];
                $padre = [];
                $junt = [];
                $BOM = MStructure::where('final', $plans->IPROD)->get()->toarray();
                array_push($padre, $plans->IPROD, $plans->ICLAS);
                array_push($junt, $padre);
                if (count($BOM) > 1) {
                    foreach ($BOM as $comp1) {
                        $par = [];
                        $pad = '';
                        $res1 = MStructure::query()
                            ->select('final')
                            ->where('componente',  $comp1['Componente'])->where('clase', '!=', '01')
                            ->distinct('final')
                            ->get()->toarray();
                        if (count($res1) > 1) {

                            foreach ($res1 as $padres) {
                                $pad = $pad . ',' . $padres['final'];
                            }
                        }else{
                           $pad=$plans->IPROD;
                        }

                        array_push($par, $pad, $comp1['Componente'],  $comp1['Clase'],$comp1['Activo']);
                        array_push($cF1, $par);
                    }
                    array_push($junt, $cF1);
                }



                array_push($tot, $junt);
            }
        }


        return view('planeacion.VerEstructura', ['nombre' => $nombre, 'plan' => $plan, 'total' => $tot, 'LWK' => $WCs, 'SEpro' => $Pr]);
    }
    function cargarestructura($prod)
    {
        $in = array();
        $in = ['final' => $prod];
        $res = MStructure::query()
            ->select('Final', 'Componente', 'Activo')
            ->where('Final', $prod)
            ->where([
                ['clase', '!=', '01'],
            ])
            ->get()->toarray();
        foreach ($res as $comp1) {
            $hijos = [];
            $res1 = MStructure::query()
                ->select('final')
                ->where('componente',  $comp1['Componente'])
                ->distinct('final')
                ->get();
            $padres = '';
            foreach ($res1 as $final) {
                $padres = $final->final . ' \n ' . $padres;
            }

            array_push($hijos, $padres);
            array_push($hijos, $comp1['Componente']);
            $in += [$hijos];
        }
        return $in;
    }

    public function update(Request $request)
    {

        $Pr = $request->SeProject;
        $variables = $request->all();
        $keyre = array_keys($variables);
        foreach ($keyre as $chek) {

            $namenA = strtr($chek, '_', ' ');
            MStructure::query()
                ->where('Componente',  $namenA)
                ->update(['Activo' => $request->$chek]);
        }
        $WCs = ZCC::query()
            ->select('CCID', 'CCTABL', 'CCCODE', 'CCDESC')
            ->where('CCID', '=', 'CC')
            ->Where('CCTABL', '=', 'SIRF4')
            ->orderBy('CCID', 'ASC')
            ->get();

        $plan = IPB::query()
            ->select('IPROD')
            ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
            ->where([
                ['IREF04', 'like', '%' . $Pr . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->distinct('IPROD')
            ->get();
        $totestructura = array();
        foreach ($plan as $plans) {
            $inf = self::cargarestructura($plans['IPROD']);
            array_push($totestructura, $inf);
        }

        return view('planeacion.VerEstructura', ['plan' => $plan, 'total' => $totestructura, 'LWK' => $WCs, 'SEpro'  => $Pr]);
    }
    public function export(Request $request)
    {
        $Pr =  $request->SeProject ?? '*';
        return Excel::download(new UsersExport($Pr), 'Estructura' . $Pr . '.xlsx');
    }
}
