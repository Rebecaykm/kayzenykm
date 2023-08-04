<?php

namespace App\Http\Controllers;

use App\Models\IIM;
use Illuminate\Http\Request;
use App\Models\IPB;
use App\Models\ZCC;
use App\Models\MStructure;
use App\Models\MBM;


class Structure extends Controller
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
        $plan = IIM::query()
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
            ->distinct('IPROD')->get();
            $tot = [];
        if ($Pr != '*') {
            $fin = [];
            $cF1 = [];
            $BOM = [];
            foreach ($plan as $plans) {
                $num = [];
                $par = [];
                array_push($par, $plans->IPROD, $plans->ICLAS);
                array_push($cF1, $par);
                $cF1 += self::buscarF1($plans->IPROD, $plans->IPROD);
                // array_push($fin, $cF1);
                array_push($num, $par);
                $BOM = MStructure::where('final', $plans->IPROD)->get()->toarray();
                array_push($num, $BOM);
                array_push($tot, $num);
            }
        }



        return view('planeacion.Estructura', ['cF1' => $tot, 'plan' => $plan, 'LWK' => $WCs, 'SEpro' => $Pr]);
    }

    function guardar($prod, $sub, $clase)
    {
        $res = self::buscar($prod, $sub);
        if ($res == 0) {
            $data = MStructure::create([
                'final' => $prod,
                'componente' => $sub,
                'clase' => $clase,
                'Activo' => '1',
            ]);
        }
    }
    function buscar($prod, $sub)
    {
        $data = MStructure::query()
            ->select('Final', 'Componente')
            ->where('Final', '=', $prod)
            ->where('Componente', '=', $sub)
            ->count();

        return $data;
    }
    function buscarF1($prod, $final)
    {
        $a = array(array());
        $i = count($a);
        $hijo = self::Hijo($prod);
        foreach ($hijo as $hijos) {
            $Chijo = self::Conthijo($hijos->BCHLD);
            if ($Chijo != 0) {
                $b = self::buscarF1($hijos->BCHLD, $final);
                $i = count($a);
                foreach ($b as $bs) {
                    $j = 0;
                    foreach ($bs as $valor) {
                        $a[$i][$j] = $valor;
                        $j++;
                    }
                    $i++;
                }
            }
            $a[$i][0] = $hijos->BCHLD;
            $a[$i][1] = $hijos->BCLAC;

            self::guardar($final, $hijos->BCHLD,  $hijos->BCLAC);
            $i++;
        }
        return $a;
    }
    function Conthijo($prod)
    {
        $ContBMS = MBM::query()
            ->select('BPROD')
            ->join('LX834F01.IIM', 'LX834F01.IIM.IPROD', '=', 'LX834F01.MBM.BPROD')
            ->where('BPROD', '=', $prod)
            ->where('IMPLC', '!=', 'OBSOLETE')
            ->where(function ($query) {
                $query->where('BCLAC ', 'M2')
                    ->orwhere('BCLAC ', 'M3')
                    ->orwhere('BCLAC ', '01')
                    ->orwhere('BCLAC ', 'M4');
            })
            ->count();
        return $ContBMS;
    }
    function Hijo($prod)
    {
        $MBMS = MBM::query()
            ->select('BCHLD', 'BCLAC')
            ->where('BPROD', '=', $prod)
            ->where(function ($query) {
                $query->where('BCLAC ', 'M2')
                    ->orwhere('BCLAC ', 'M3')
                    ->orwhere('BCLAC ', '01')
                    ->orwhere('BCLAC ', 'M4');
            })
            ->orderby('BCHLD')
            ->get();

        return $MBMS;
    }
    function Projecto($proj)
    {
        $PCs = ZCC::query()
            ->select('CCDESC')
            ->where([['CCID', '=', 'CC'], ['CCTABL', '=', 'SIRF4'], ['CCCODE', '=', $proj]])
            ->first();

        return $PCs;
    }
}
