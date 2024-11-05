<?php

namespace App\Http\Controllers;

use App\Models\IIM;
use App\Models\YMLTM;
use Illuminate\Http\Request;

class OffSetitemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Step1.index');
    }



     public function store(Request $request)
    {
    $CLAS=['M2','M3','M4'];
    $f1=$request->item;
    $ofs=IIM::query()
    ->rightjoin('LX834FU01.YMCOM', 'IPROD', '=', 'MCFPRO')
    ->leftjoin('LX834FU01.YMLTM', 'MCCPRO', '=', 'LTPROD')
    ->select('IPROD','MCCPRO', 'MCFPRO','MCCCLS','LTLDTM','LTPROD')
                ->where([
                    ['IID', '!=', 'IZ'],
                    ['IMPLC', '!=', 'OBSOLETE'],
                ])
                ->Wherein('MCCCLS', $CLAS)
                ->where('IPROD',$f1)
                ->get();


    $padres=YMLTM::query()
    ->rightjoin('LX834F01.IIM as iimF', 'LTPROD', '=', 'iimF.IPROD')
    ->rightjoin('LX834FU01.YMCOM ', 'iimF.IPROD', '=', 'MCFPRO')
    ->select('iimF.IPROD','MCCPRO', 'MCFPRO','MCFCLS','MCCCLS','LTPROD','LTLDTM','LTCCDT','LTCCTM','LTFIL1','LTFIL2')
                ->where([
                    ['iimF.IID', '!=', 'IZ'],
                    ['iimF.IMPLC', '!=', 'OBSOLETE'],
                ])
                ->Wherein('MCCCLS', $CLAS)
                ->wherein('MCCPRO',$ofs->pluck('MCCPRO'))
                ->get();
foreach($ofs as $part)
{
    $arr= $padres->where('MCCPRO',$part->MCCPRO)->where('MCFPRO','!=', $part->MCCPRO)->where('MCFCLS','!=','F1');

    $part->setAttribute('padres', $arr->pluck('MCFPRO')->map(fn($value) => (string) $value)->uniqueStrict()->toArray());

}


                return view('Step1.leadtime', ['partes' =>$ofs,'F1'=>$f1]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $load = date('Ymd', strtotime('now'));
        $hora = date('His', time());
        $padre=$request->F1;


        $verfica=YMLTM::where('LTPROD', $request->item)->get();


        if($verfica->count()>0)
        {

            if($verfica->count()>=2)
            {
                $deleted = YMLTM::where('LTPROD', $request->item)->delete();
                $dfa = [
                    'LTID'=>'LT',
                    'LTPROD'=>$request->item,
                    'LTLDTM'=>$request->cantidad,
                    'LTCUSR'=>'LXSECOFR',
                    'LTCCDT'=>$load,
                    'LTCCTM'=>$hora,
                    'LTFIL1'=>'',
                    'LTFIL2'=>''

                ];
                $indata = YMLTM::query()->insert($dfa);
            }else{
                if($request->cantidad== 0)
                {
                    $deleted = YMLTM::where('LTPROD', $request->item)->delete();
                }else{
                    $updated = YMLTM::where('LTPROD', $request->item)
                    ->update(['LTLDTM' => $request->cantidad,'LTCUSR'=>'LXSECOFR',
                        'LTCCDT'=>$load,
                        'LTCCTM'=>$hora,]);
                }

            }


        }else
        {
            $dfa = [
                'LTID'=>'LT',
                'LTPROD'=>$request->item,
                'LTLDTM'=>$request->cantidad,
                'LTCUSR'=>'LXSECOFR',
                'LTCCDT'=>$load,
                'LTCCTM'=>$hora,
                'LTFIL1'=>'',
                'LTFIL2'=>''

            ];
            $indata = YMLTM::query()->insert($dfa);
        }


        return redirect()->route('offset.index')
        ->with('mensaje', 'Registro creado exitosamente.');
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
