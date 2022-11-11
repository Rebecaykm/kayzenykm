<?php

namespace App\Http\Controllers;

use App\Models\Iim;
use Illuminate\Http\Request;
use App\Models\IPB;
use App\Models\ZCC;
use App\Models\Structure;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;


class ShowStructure extends Controller
{

    public function export(){
        return Excel::download(new UsersExport, 'users.xlsx');
    }

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
            ->select('IPROD')
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
            ->get();
        return view('planeacion.VerEstructura', ['plan' => $plan, 'LWK' => $WCs, 'SEpro' => $Pr]);
    }

    public function update(Request $request)
    {

        $Pr = $request->SeProject;

        $variables = $request->all();

        $keyre = array_keys($variables);

        foreach ($keyre as $chek) {
            $namenA = strtr($chek, '_', ' ');
            Structure::query()
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
            ->simplePaginate(30);
        return view('planeacion.VerEstructura', ['plan' => $plan, 'LWK' => $WCs, 'SEpro'  => $Pr]);
    }

}
