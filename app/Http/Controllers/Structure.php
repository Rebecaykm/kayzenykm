<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IPB;

class Structure extends Controller
{
    public function index(Request $request)
    {

        $plan = IPB::query()
            ->select('IPROD', 'ICLAS', 'IREF04', 'IID', 'IMPLC', 'IBUYC', 'IMPLC')
            ->join('LX834F01.IIM', 'LX834F01.IIM.IBUYC', '=', 'LX834F02.IPB.PBPBC')
            ->join('LX834F01.FRT', 'LX834F01.FRT.RPROD', '=', 'LX834F01.IIM.IPROD ')
            ->join('LX834F01.LWK', 'LX834F01.FRT.RWRKC', '=', 'LX834F01.LWK.WWRKC ')
            ->where([
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
            ])
            ->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->distinct('IPROD')
            ->simplePaginate(30);
            return view('planeacion.Estructura', ['plan' => $plan]);

    }
}
