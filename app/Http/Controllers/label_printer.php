<?php

namespace App\Http\Controllers;

require  'C:\label\eps.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea

use Mike42\Escpos\EscposImage;
use Illuminate\Http\Request;


use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

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

        $nombre_impresora = 'Intermec';

        try {

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);
            $command = '
            <STX>SIZE 4,3<ETX>
            <STX>R<ETX>
            <STX><ESC>C<SI>W1154<SI>h<ETX>
            <STX><ESC>P<ETX><STX>F*<ETX>
            <STX>H1;f3;o796,9;c69;b0;h18;w19;d3,Line production<ETX>
            <STX>L2;f0;o752,408;l50;w2<ETX>
            <STX>L3;f1;o801,409;l409;w2<ETX>
            <STX>L4;f1;o753,409;l409;w2<ETX>
            <STX>B5;f3;o276,51;c0,6;w7;h120;r0;d3,12345678<ETX>
            <STX>H6;f3;o144,498;c66;b0;h17;w16;d3,12345678<ETX>
            <STX>H7;f3;o604,51;c66;b0;h21;w40;d3,umero de parte<ETX>
            <STX>H8;f3;o872,10;c69;b0;h17;w17;d3,Production Date<ETX>
            <STX>L9;f0;o830,261;l49;w2<ETX>
            <STX>L10;f1;o878,262;l262;w2<ETX>
            <STX>L11;f1;o831,262;l262;w2<ETX>
            <STX>H12;f3;o867,540;c69;b0;h17;w17;d3,Shop order No.<ETX>
            <STX>W13;f0;o825,506;h291;l48;w2<ETX>
            <STX>H14;f3;o651,491;c69;b0;h17;w17;d3,Parts number <ETX>
            <STX>L15;f0;o634,10;l23;w2<ETX>
            <STX>L16;f1;o657,34;l23;w2<ETX>
            <STX>L17;f1;o657,1154;l1120;w2<ETX>
            <STX>L18;f1;o657,1177;l23;w2<ETX>
            <STX>L19;f0;o634,1176;l23;w2<ETX>
            <STX>L20;f0;o611,1176;l22;w2<ETX>
            <STX>L21;f1;o611,1177;l23;w2<ETX>
            <STX>L22;f1;o611,1154;l1120;w2<ETX>
            <STX>L23;f1;o611,34;l23;w2<ETX>
            <STX>L24;f0;o611,10;l22;w2<ETX>
            <STX>H25;f3;o493,51;c66;b0;h21;w40;d3,umero de parte<ETX>
            <STX>H26;f3;o727,49;c66;b0;h21;w38;d3,umero de partekw wknkwnknwkn<ETX>
            <STX>H27;f3;o867,294;c66;b0;h21;w41;d3,06/09<ETX>
            <STX>H28;f3;o867,840;c66;b0;h21;w41;d3,12345678<ETX>
            <STX>H29;f3;o541,489;c69;b0;h17;w17;d3,Parts name <ETX>
            <STX>L30;f0;o500,1159;l48;w2<ETX>
            <STX>L31;f1;o547,1160;l1160;w2<ETX>
            <STX>L32;f1;o501,1160;l1160;w2<ETX>
            <STX>H33;f3;o427,917;c69;b0;h19;w19;d3,Seq<ETX>
            <STX>W34;f0;o380,732;h426;l53;w2<ETX>
            <STX>H35;f3;o427,320;c69;b0;h19;w19;d3,NSP<ETX>
            <STX>W36;f0;o380,204;h288;l53;w2<ETX>
            <STX>H37;f3;o429,71;c69;b0;h20;w21;d3,R/L<ETX>
            <STX>L38;f0;o380,204;l53;w2<ETX>
            <STX>L39;f1;o432,205;l205;w2<ETX>
            <STX>L40;f1;o381,205;l205;w2<ETX>
            <STX>H41;f3;o427,546;c69;b0;h19;w19;d3,Pack -c<ETX>
            <STX>W42;f0;o380,490;h244;l53;w2<ETX>
            <STX>H43;f3;o355,58;c66;b0;h34;w67;d3,R<ETX>
            <STX>H44;f3;o355,306;c66;b0;h34;w67;d3,R<ETX>
            <STX>H45;f3;o355,569;c66;b0;h34;w67;d3,R<ETX>
            <STX>H46;f3;o352,764;c66;b0;h29;w57;d3,001-999<ETX>
            <STX>H47;f3;o58,47;c66;b0;h25;w25;d3,Identifaction Card <ETX>
            <STX>H48;f3;o50,662;c66;b0;h15;w14;d3,Y-tec Keylex Mexico S.A de C.V <ETX>
            <STX>D0<ETX>
            <STX>R<ETX>
            <STX><SI>l13<ETX>
            <STX><ESC>E*,1<CAN><ETX>
            <STX><RS>1<US>1<ETB><ETX>    ;

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
