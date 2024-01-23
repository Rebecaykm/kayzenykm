<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataArray = [];

        $dataArray = [
            [
                'id' => 1,
                'departament' => 'CHASIS',
                'projects' => ['J59J', 'J59W'],
                'class' => 'M1',
                'workcenterNumber' => '139050',
                'workcenterName' => 'LSR BAT-20',
                'partNumber' => 'BDTS28B01',
                'date' => '2023/10/19',
                'shift' => 'D',
                'container' => 'CARRO',
                'quantity' => '30',
                'sequence' => '001',
                'a' => '*** ORIGINAL ***'
            ],
            [
                'id' => 1,
                'departament' => 'CHASIS',
                'projects' => ['J59J', 'J59W'],
                'class' => 'M1',
                'workcenterNumber' => '139050',
                'workcenterName' => 'LSR BAT-20',
                'partNumber' => 'BDTS28B01',
                'date' => '2023/10/19',
                'shift' => 'D',
                'container' => 'CARRO',
                'quantity' => '30',
                'sequence' => '001',
                'a' => '*** ORIGINAL ***'
            ]
        ];

        $dataArrayWithQr = [];

        foreach ($dataArray as $key => $data) {
            $qrData = $data['id'] . $data['partNumber'] . $data['quantity'] . $data['sequence'] . Carbon::parse($data['date'])->format('Ymd') . $data['shift'];
            $qrCodeData = QrCode::size(600)->format('svg')->generate($qrData);
            $data['qrCode'] = $qrCodeData;

            $dataArrayWithQr[] = $data;
        }

        // $pdf = Pdf::loadView('label', ['dataArrayWithQr' => $dataArrayWithQr])->setOptions(['defaultFont' => 'sans-serif']);
        // return $pdf->stream('resume.pdf');

        return View::make('label-example', ['dataArrayWithQr' => $dataArrayWithQr]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
