<?php

namespace App\Http\Controllers;

// require  'C:\label\eps.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
// require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\EscposImage;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class label_printer extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $nombre_impresora = 'Intermec';
        $nombre_impresora = 'testipl';
        // $connector = new NetworkPrintConnector('192.168.180.163', 'USB003');

        // $connector = new NetworkPrintConnector("192.168.120.44", 9100);


        try {
            $connector = new WindowsPrintConnector('2500');

            $printer = new Printer($connector);
            dd( $printer );
            $command = '

       R CW816 PF*
       H1;f3;o558,22;c61;b0;h12;w14;d3,Departamento
       H2;f3;o526,34;c61;b0;h20;w10;d3,%Departamento%

       L50;f0;o481,345;l100;w3
       H3;f3;o558,350;c61;b0;h12;w14;d3,Estacion
       H4;f3;o526,350;c61;b0;;h20;w10;d3,%Estacion%
       L51;f0;o481,580;l100;w3
       H5;f3;o558,584;c61;b0;h12;w14;d3,Proyecto
       H6;f3;o526,584;c61;b0;;h20;w10;d3,%Proyecto%
       L25;f1;o481,809;l787;w3
       H7;f3;o480,22;c61;b0;;h20;w10;d3,Part number
       H8;f3;o440,100;c68;b0;h26;w26;d3,%Part number %
       L26;f1;o381,809;l787;w3
       H9;f3;o380,22;c61;b0;h12;w14;d3,Fecha de produccion
       H10;f3;o350,22;c61;b0;h20;w10;d3,%20/03/2024 D%
       L52;f0;o306,415;l80;w3
       H11;f3;o380,420;c61;b0;h12;w14;d3,Consecutivo
       H12;f3;o350,420;c61;b0;h20;w10;d3,%001%
       L27;f1;o306,809;l787;w3
       H13;f3;o305,22;c61;b0;h12;w14;d3,Contenedor
       H14;f3;o280,34;c61;b0;h20;w10;d3,%rollpack%
       L53;f0;o231,345;l80;w3
       H15;f3;o305,350;c61;b0;h12;w14;d3,SNP
       H16;f3;o280,350;c61;b0;;h20;w10;d3,%SPN%
       L28;f1;o231,809;l787;w3
       H17;f3;o230,22;c61;b0;h12;w14;d3,Cantidad producida
       H18;f3;o210,22;c61;b0;;h20;w10;d3,%1500%
       H19;f3;o120,22;c61;b0;h8;w14;d3,IDENTIFATION CARD
       H20;f3;o100,22;c61;b0;;h10;w10;d3,%REIMPRESION%
       H21;f3;o80,22;c61;b0;;h8;w10;d3,%FECHA DE IMPRESION %
       H23;f3;o60,22;c61;b0;h10;w14;d3,%2024/03/20 18:15:20%
       H22;f3;o20,22;c61;b0;h8;w14;d3,Y-TEC KEYLEX MEXICO
       B24;o0,450;c17,200,0;w5;h5;d3,%SERIAL%%part_number%%estacion%
       D0
       R
       l13
       E*,1
       1
       1
          ';
            $printer->getPrintConnector()->write($command);
            $printer->getPrintConnector()->finalize();
        } catch (\Exception $e) {
            return "No se pudo establecer la conexión con la impresora: " . $e->getMessage();
        }
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
