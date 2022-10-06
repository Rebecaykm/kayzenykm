<?php

namespace App\Http\Controllers;

use App\Models\Fso;
use App\Models\Lwk;
use App\Models\Yf006;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyProductionController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $area = $request->area ?? '';
        $work = $request->workCenter ?? '';
        $date = $request->dueDate != '' ? Carbon::parse($request->dueDate)->format('Ymd') : '';

        $dailyDiurno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID', 'SWRKC', 'IOPB', 'IRCT', 'IISS', 'IADJ', 'IMSPKT', 'IMBOXQ'])
            ->join('LX834F02.IIM', 'LX834F02.IIM.IPROD', '=', 'LX834F02.FSO.SPROD')
            ->where([
                ['SDDTE', '=', $date],
                ['SWRKC', 'LIKE', '%' . $work . '%'],
                ['SWRKC', 'LIKE', $area . '%'],
                ['SOCNO', 'NOT LIKE', '%N%']
            ])
            ->orderBy('SOCNO', 'ASC')
            ->get();

        $dailyNocturno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID', 'SWRKC', 'IOPB', 'IRCT', 'IISS', 'IADJ', 'IMSPKT', 'IMBOXQ'])
            ->join('LX834F02.IIM', 'LX834F02.IIM.IPROD', '=', 'LX834F02.FSO.SPROD')
            ->where([
                ['SDDTE', '=', $date],
                ['SWRKC', 'LIKE', '%' . $work . '%'],
                ['SWRKC', 'LIKE', $area . '%'],
                ['SOCNO', 'LIKE', '%N%']
            ])
            ->orderBy('SOCNO', 'ASC')
            ->get();

        if (strncmp($work, '11', 2) === 0 || strncmp($area, '11', 2) === 0) {
            $areaName = 'Estampado';
        } elseif (strncmp($work, '12', 2) === 0 || strncmp($area, '12', 2) === 0) {
            $areaName = 'Carrocería';
        } elseif (strncmp($work, '13', 2) === 0 || strncmp($area, '13', 2) === 0) {
            $areaName = 'Chasis';
        } elseif (strncmp($work, '14', 2) === 0 || strncmp($area, '14', 2) === 0) {
            $areaName = 'Pintura';
        } elseif (strncmp($work, '40', 2) === 0 || strncmp($area, '40', 2) === 0) {
            $areaName = 'Proveedor';
        } else {
            $areaName = 'Área no Definida';
        }

        $countDiurno = $dailyDiurno->count();
        $countNocturno = $dailyNocturno->count();

        $date = $request->dueDate != '' ? Carbon::parse($request->dueDate)->format('d-m-Y') : 'Fecha no Definida';

        return view('dailyProduction.index', [
            'dailyDiurnos' => $dailyDiurno,
            'dailyNocturnos' => $dailyNocturno,
            'area' => $areaName,
            'date' => $date,
            'countDiurno' => $countDiurno,
            'countNocturno' => $countNocturno,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexUser(Request $request)
    {
        $area = $request->area ?? '';
        $work = $request->workCenter ?? '';
        $date = $request->dueDate != '' ? Carbon::parse($request->dueDate)->format('Ymd') : '';

        $dailyDiurno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID', 'SWRKC', 'IOPB', 'IRCT', 'IISS', 'IADJ', 'IMSPKT', 'IMBOXQ'])
            ->join('LX834F02.IIM', 'LX834F02.IIM.IPROD', '=', 'LX834F02.FSO.SPROD')
            ->where([
                ['SDDTE', '=', $date],
                ['SWRKC', 'LIKE', '%' . $work . '%'],
                ['SWRKC', 'LIKE', $area . '%'],
                ['SOCNO', 'NOT LIKE', '%N%'],
            ])
            ->orderBy('SOCNO', 'ASC')
            ->get();

        $dailyNocturno = Fso::query()
            ->select(['SOCNO', 'SPROD', 'SORD', 'SQREQ', 'SQFIN', 'SQREMM', 'SID', 'SWRKC', 'IOPB', 'IRCT', 'IISS', 'IADJ', 'IMSPKT', 'IMBOXQ'])
            ->join('LX834F02.IIM', 'LX834F02.IIM.IPROD', '=', 'LX834F02.FSO.SPROD')
            ->where([
                ['SDDTE', '=', $date],
                ['SWRKC', 'LIKE', '%' . $work . '%'],
                ['SWRKC', 'LIKE', $area . '%'],
                ['SOCNO', 'LIKE', '%N%'],
            ])
            ->orderBy('SOCNO', 'ASC')
            ->get();

        if (strncmp($work, '11', 2) === 0 || strncmp($area, '11', 2) === 0) {
            $areaName = 'Estampado';
        } elseif (strncmp($work, '12', 2) === 0 || strncmp($area, '12', 2) === 0) {
            $areaName = 'Carrocería';
        } elseif (strncmp($work, '13', 2) === 0 || strncmp($area, '13', 2) === 0) {
            $areaName = 'Chasis';
        } elseif (strncmp($work, '14', 2) === 0 || strncmp($area, '14', 2) === 0) {
            $areaName = 'Pintura';
        } elseif (strncmp($work, '40', 2) === 0 || strncmp($area, '40', 2) === 0) {
            $areaName = 'Proveedor';
        } else {
            $areaName = 'Área no Definida';
        }

        $countDiurno = $dailyDiurno->count();
        $countNocturno = $dailyNocturno->count();

        $date = $request->dueDate != '' ? Carbon::parse($request->dueDate)->format('d-m-Y') : 'Fecha no Definida';

        return view('dailyProduction.user', [
            'dailyDiurnos' => $dailyDiurno,
            'dailyNocturnos' => $dailyNocturno,
            'area' => $areaName,
            'date' => $date,
            'countDiurno' => $countDiurno,
            'countNocturno' => $countNocturno,
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'arrayDailyProductions' => 'required|array',
        ]);

        foreach ($request->arrayDailyProductions as $arrayDaily) {
            $sqfin = $arrayDaily['sqfin'] ?? '0';
            $sqremm = $arrayDaily['sqremm'] ?? '0';
            $cdte = !isset($arrayDaily['cdte']) ? $cdte = '' :  Carbon::parse($arrayDaily['cdte'])->format('Ymd');
            $canc = $arrayDaily['canc'] ?? 0;

            if ($cdte !== '' || $canc !== 0 || $sqfin !== '0' || $sqremm !== '0') {
                $data = Fso::query()
                    ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN', 'SQREMM'])
                    ->where('SORD', '=', $arrayDaily['sord'])
                    ->first();
                if ($data->SID == 'SO') {
                    $insert = Yf006::storeDailyProduction($data->SID, $data->SWRKC, $data->SDDTE, $data->SORD, $data->SPROD, $data->SQREQ, $sqfin, $sqremm, $canc, $cdte);
                }
            }
        }

        $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
        $query = "CALL LX834OU02.YSF008C";
        $result = odbc_exec($conn, $query);

        if ($result) {
            return redirect()->back()->with('success', 'Registro(s) Actuaizado(s) con Éxito');
        } else {
            return redirect()->back()->with('danger', '¡Oh no! Algo salió mal.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
