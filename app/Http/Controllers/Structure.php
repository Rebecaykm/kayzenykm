<?php

namespace App\Http\Controllers;

use App\Models\Iim;
use Illuminate\Http\Request;
use App\Models\IPB;
use App\Models\ZCC;

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


        $plan = Iim::query()
            ->select('IPROD', 'ICLAS', 'IREF04', 'IID', 'IMPLC', 'IBUYC', 'IMPLC')
            ->where([
                ['IREF04', 'like', '%' . $Pr . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
                ['IPROD','NOT LIKE', '%-830%'],
            ])
            ->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->distinct('IPROD')
            ->simplePaginate(30);













        return view('planeacion.Estructura', ['plan' => $plan, 'LWK' => $WCs, 'SEpro' => $Pr]);
    }
}
