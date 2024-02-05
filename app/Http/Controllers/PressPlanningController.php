<?php

namespace App\Http\Controllers;

use App\Models\Workcenter;
use App\Models\PartNumber;
use App\Models\part_hierarchies;
use Illuminate\Http\Request;
use App\Models\KFP;
use App\Models\FSO;
use App\Models\HPO;

class PressPlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {

        $press = Workcenter::query()
            ->select('*')
            ->where('departament_id', '2')
            ->get()->toArray();
        return view('pressplanning.index', ['wc' => $press]);
    }


    public function period(Request $request)
    {

        $numberparts = PartNumber::where('workcenter_id', $request->SePress)->get();

        $numberpartsArray = PartNumber::where('workcenter_id', $request->SePress)->get()->toarray();
        $PartNumberId = array_column($numberpartsArray, 'number');
        $hoy = $request->fecha;
        $hoy = date('Ymd', strtotime($request->fecha));
        $dias = 6;
        $totalF = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));


        $rel = [];
        $bomin = [];
        $mat = [];
        foreach ($numberparts  as $fsopart) {
            //    dd( $fsopart,$fsopart->subPartNumbers);

            $bomnum = [];
            $bomin = [];
            $arr = array_column($fsopart->subPartNumbers->toArray(), 'number');
            $reversed = array_reverse($arr);

            if (!in_array($reversed[0],   $mat)) {
                array_push($mat, $reversed[0]);
                $bomin += ['Mat' => $reversed[0]];
                unset($reversed[0]);
                array_push($bomnum, $reversed);
                $bomin += ['parts' => $reversed];
                array_push($rel, $bomin);
            } else {

                $pos = array_search($reversed[0], $mat);
                unset($reversed[0]);
                $var = $rel[$pos];
                foreach ($reversed as $p2) {
                    if (!array_search($p2, $var['parts'])) {
                        array_push($var['parts'], $p2);
                    }
                }
                $rel[$pos] =  $var;
            }
        }
        $part = [];
        foreach (array_column($rel, 'parts') as $parts) {
            foreach ($parts as $pq) {
                array_push($part, $pq);
            }
        }



        $valPDp = FSO::query()
            ->select('SPROD', 'SDDTE',  'SOCNO', 'SQREQ', 'SQFIN')
            ->wherein('SPROD', $part)
            ->where('SDDTE', '>=', $hoy)
            ->where('SDDTE', '<', $totalF)
            ->get()->toarray();
            $ifso = Array_column($valPDp, 'SPROD');

        $ultfso = FSO::query()
            ->select('SPROD', FSO::raw('MAX(SDDTE) AS SDDTE1 '), FSO::raw(' max(SOCNO) AS SOCNO'), FSO::raw('MAX(SQREQ) AS SREQ'), FSO::raw('MAX(SQFIN) AS REAL'))
            ->wherein('SPROD', $part)
            ->where('SDDTE', '<=', $totalF)
            ->ORDERBY('SDDTE1', 'DESC')
            ->groupBy('SPROD')
            ->get()->toarray();

        $itemfso = Array_column($ultfso, 'SPROD');

        $valHPO = HPO::query()
            ->select('PORD', 'PLINE', 'PPROD', 'PQORD', 'PQREC', 'PDDTE')
            ->wherein('PPROD', array_column($rel, 'Mat'))
            ->where('PDDTE', '>=', $hoy)
            ->where('PDDTE', '<', $totalF)
            ->get()->toarray();
        $itemHPO = Array_column($valHPO, 'PPROD');

        $ultHPo = HPO::query()
            ->select('PPROD', HPO::raw('MAX(PDDTE) AS PDDTE1 '), HPO::raw(' max(PORD) AS PORD'), HPO::raw('MAX(PQORD) AS PQREC'), HPO::raw('MAX(PQREC) AS REAL'), HPO::raw('MAX(PLINE) AS PLINE'))
            ->wherein('PPROD', array_column($rel, 'Mat'))
            ->where('PDDTE', '<=', $totalF)
            ->ORDERBY('PDDTE1', 'DESC')
            ->groupBy('PPROD')
            ->get()->toarray();
        $itemultHPO = Array_column($ultHPo, 'PPROD');


        $bom = [];


        foreach ($rel as $arre) {
            $data = [];

            $datos = [];
            $fechaval = [];
            $pos = array_search($arre['Mat'],   $itemultHPO);
            $parts = [];
            $parts += ["mat" => $arre['Mat']];
            if ($pos !== false) {
                $datos = $ultHPo[$pos];
                $fechaval += ['Fecha' => $datos['PDDTE1']];
                $fechaval += ['R' => $datos['PQREC']];
                $fechaval += ['F' => $datos['REAL']];
                $fechaval += ['orden' => $datos['PORD']];
                $fechaval += ['LINEA' => $datos['PLINE']];
                $parts += ["ultimaorden" => $fechaval];

            }else
            {$fechaval += ['Fecha' => ''];
                $fechaval += ['R' => ''];
                $fechaval += ['F' => ''];
                $fechaval += ['orden' =>''];
                $fechaval += ['LINEA' => ''];
                $parts += ["ultimaorden" => $fechaval];
            }


            $totalsemana=[];
            $semana=[];
            while($vl=array_search($arre['Mat'],  $itemHPO)!==false)
            {
                $semana=[];
                // echo  $arre['Mat'].'<br>';
                $pos=array_search($arre['Mat'],  $itemHPO);
                $valarre= $valHPO[$pos];
                $semana += [ 'R' =>   $valarre['PQORD']];
                $semana += [  'F' =>  $valarre['PQREC']];
                $semana+= ['orden' =>   $valarre['PORD']];
                $semana += ['LINEA' =>   $valarre['PLINE']];
                unset($itemHPO[$pos]);
               $totalsemana+=[$valarre['PDDTE']=>$semana];
            }

            $semana=[];

            $parts += ["semana" =>$totalsemana];
            $data += ["Mat" => $parts];

            $itemHPO = Array_column($valHPO, 'PPROD');
            $fechaval = [];
            $hijo = [];
            foreach ($arre['parts'] as $part1) {
                $parts = [];
                $pos1 = array_search($part1,   $itemfso);
                $dat = $ultfso[$pos1];
                $parts += ['parte' => $part1];
                $fechaval = ['Fecha' => $dat['SDDTE1']];
                $fechaval += ["R" => $dat['SREQ']];
                $fechaval += ["F" => $dat['REAL']];
                $fechaval += ["TURNO" => $dat['SOCNO']];
                $parts += ['ultimaorden' => $fechaval];


                $totalsemana=[];

                while($vl1=array_search($part1,    $ifso )!==false)
                {

                    $semana=[];
                    // echo  $part1.'<br>';
                    $pos=array_search($part1, $ifso);
                    $valarre= $valPDp[$pos];
                    $semana += [  'R' =>   $valarre['SQREQ']];
                    $semana += [  'F' =>  $valarre['SQFIN']];
                    unset( $ifso[$pos]);
                    $totalsemana+=[$valarre['SDDTE']=>$semana];
                }
                $parts += ["semana" =>$totalsemana];
                array_push($hijo,  $parts);
                $ifso = Array_column($valPDp, 'SPROD');

            }

            $data += ["hijo" =>  $hijo];
            array_push($bom, $data);
            $ifso = Array_column($valPDp, 'SPROD');
        }






        return view('pressplanning.plancomponente', ['res' => $bom,   'fecha' => $request->fecha, 'dias' => $dias]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

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
