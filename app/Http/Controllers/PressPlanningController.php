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
        foreach ($numberparts  as $fsopart) {
            //    dd( $fsopart,$fsopart->subPartNumbers);

            foreach (array_column($fsopart->subPartNumbers->toArray(), 'number') as $num) {
                array_push($rel, $num);
            }
        }

        $valPDp = FSO::query()
            ->select('SPROD', 'SDDTE',  'SOCNO', 'SQREQ', 'SQFIN')
            ->wherein('SPROD', $rel)
            ->where('SDDTE', '>=', $hoy)
            ->where('SDDTE', '<', $totalF)
            ->get()->toarray();

        $ultfso = FSO::query()
            ->select('SPROD',FSO::raw('MAX(SDDTE) AS SDDTE1 '),FSO::raw(' max(SOCNO) AS SOCNO'), FSO::raw('MAX(SQREQ) AS SREQ'), FSO::raw('MAX(SQFIN) AS REAL'))
            ->wherein('SPROD', $rel)
            ->where('SDDTE','<=',$totalF)
            ->ORDERBY('SDDTE1','DESC')
            ->groupBy('SPROD')
            ->get()->toarray();

            $itemfso=Array_column($ultfso,'SPROD');
            $datfso=Array_column($ultfso,'SDDTE1');
            $nofso=Array_column($ultfso,'SOCNO');
            $reqfso=Array_column($ultfso,'SREQ');
            $finfso=Array_column($ultfso,'REAL');
        $valHPO = HPO::query()
            ->select('PORD', 'PLINE', 'PPROD', 'PQORD', 'PQREC', 'PDDTE')
            ->wherein('PPROD', $rel)
            ->where('PDDTE', '>=', $hoy)
            ->where('PDDTE', '<', $totalF)
            ->get()->toarray();
        // $valHPO = HPO::query()
        //     ->select('PORD', 'PLINE', 'PPROD', 'PQORD', 'PQREC', 'PDDTE')
        //     ->wherein('PPROD', $rel)
        //     ->where('PDDTE', '<', $hoy)

        //     ->get()->toarray();
        $data = [];



        foreach ($rel as $key => $num) {

            $info = [];


            $pos=array_search($num, $itemfso);
            $info["prod"]=$itemfso[$pos];
            $info["fecha"] = $datfso[$pos];
            $info["R"]=$reqfso[$pos];
            $info["F"]=$finfso[$pos];





            if (strstr($num, "-MAT") == false) {
                $info = [];
                $info["parte"] = $num;
                $pos=array_search($num, $itemfso);
                $info["prod"]=$itemfso[$pos];
                $info["fecha"] = $datfso[$pos];
                $info["R"]=$reqfso[$pos];
                $info["F"]=$finfso[$pos];

                $day_qty = [];


                foreach ($valPDp as $key => $fsopartnum) {
                    $test1 = $fsopartnum['SPROD'];
                    if ($num == $test1) {

                        $day_qty += [$fsopartnum['SDDTE'] . 'R' => $fsopartnum['SQREQ']];
                        $day_qty += [$fsopartnum['SDDTE'] . 'F' => $fsopartnum['SQFIN']];
                    }
                }
                $info["produccion"] = $day_qty;
                array_push($data, $info);
            }


            if (strstr($num, "-MAT") !== false) {
                $info = [];
                $info["parte"] = $num;
                $info["parte"] = $num;
                $pos=array_search($num, $itemfso);
                $info["prod"]=$itemfso[$pos];
                $info["fecha"] = $datfso[$pos];
                $info["R"]=$reqfso[$pos];
                $info["F"]=$finfso[$pos];
                $day_hpo = [];
                foreach ($valHPO as $key => $hpopartnum) {
                    if ($num == $hpopartnum['PPROD']) {
                        $day_hpo += [$hpopartnum['PDDTE'] . 'R' => $hpopartnum['PQORD']];
                        $day_hpo += [$hpopartnum['PDDTE'] . 'F' => $hpopartnum['PQREC']];
                        // dd('hola puto ');
                    }
                }
                // para fines de vista el  se pone produccion
                $info["produccion"] = $day_hpo;

                array_push($data, $info);
            }
        }


        $bus = array_column($data, 'parte');

        $total = [];
        foreach ($numberparts  as $fsopart) {
            $arr_num = [];

            foreach (array_column($fsopart->subPartNumbers->toArray(), 'number') as $num) {


                $lug = array_search($num, $bus);


                array_push($arr_num, $data[$lug]);
            }

            array_push($total, $arr_num);
        }

        dd($total);
        return view('pressplanning.plancomponente', ['res' => $total,   'fecha' => $request->fecha, 'dias' => $dias]);
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
