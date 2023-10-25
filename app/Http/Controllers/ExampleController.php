<?php

namespace App\Http\Controllers;

require  'C:\label\eps.php';

use BaconQrCode\Encoder\QrCode;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;
use Spatie\Browsershot\Browsershot;

class ExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'departament' => 'CHASIS',
            'workcenterNumber' => '139050',
            'workcenterName' => 'LSR BAT-20',
            'date' => '2023/10/19',
            'shift' => 'D',
            'partNumber' => 'BDTS28B01',
            'container' => 'CARRO',
            'quantity' => '30',
            'sequence' => '001'
        ];

        $qrCodeData = FacadesQrCode::size(300)->generate('BDTS28B0100120231019');
        $data['qrCode'] = $qrCodeData;

        $view = View::make('label', $data);
        $html = $view->render();

        // PDF



        // $barcode = new DNS1D();
        // $barcodeData = $barcode->getBarcodeHTML('BDTS28B0100120231019', 'C39');
        // $data['barcode'] = $barcodeData;

        // $qrCodeData = QrCode::size(300)->generate('BDTS28B0100120231019');
        // $data['qrCode'] = $qrCodeData;



        // $pdf = new Dompdf();
        // $pdf->loadHtml($html);
        // $pdf->setPaper(array(0, 0, 216, 144), 'portrait');
        // $pdf->render();

        // $output = $pdf->output();

        // return response($output, 200, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="etiqueta.pdf"'
        // ]);

        // Miguel

        // $data = [
        //     'partNumber' => 'BDTS123456'
        // ];

        // $view = View::make('label', $data);
        // $html = $view->render();

        // try {

        // $connector = new WindowsPrintConnector('Intermec');
        // $printer = new Printer($connector);

        // $iplCommands = '
        //     <STX>
        //     <ESC>C<FF>
        //     <ESC>P<FF>
        //     <ESC>Q1<FF>
        //     ' . $html . '
        //     <ETX>
        // ';

        // $printer->getPrintConnector()->write($iplCommands);
        // $printer->getPrintConnector()->finalize();

        // } catch (\Exception $e) {
        //     return "No se pudo establecer la conexión con la impresora: " . $e->getMessage();
        // }

        // Impresora Termica

        // $data = [
        //     'partNumber' => 'BDTS123456'
        // ]; // Puedes pasar datos a tu vista si los necesitas

        // $view = View::make('label', $data);
        // $html = $view->render();

        // // Guardar el contenido HTML en un archivo temporal
        // $filePath = sys_get_temp_dir() . "/label.html";
        // file_put_contents($filePath, $html);

        // $connector = new FilePrintConnector($filePath);
        // $printer = new Printer($connector);

        // // Imprimir el contenido
        // $printer->text($html);

        // $printer->cut();
        // $printer->close();

        // $data = [
        //     'partNumber' => 'BDTS123456'
        // ];

        // $view = View::make('label', $data);
        // $html = $view->render();

        // // Comandos IPL para la impresora
        // $iplCommands = '
        //     <STX>
        //     <ESC>C<FF>
        //     <ESC>P<FF>
        //     <ESC>Q1<FF>
        //     ' . $html . '
        //     <ETX>
        // ';

        // $printerAddress = '192.168.1.100'; // Dirección de la impresora

        // $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        // socket_connect($socket, $printerAddress, 9100); // Puerto por defecto para IPL

        // socket_write($socket, $iplCommands, strlen($iplCommands));
        // socket_close($socket);

        // return Response::make('Printing to IPL printer...', 200);

        // $data = [
        //     'partNumber' => 'BDTS123456' // Puedes modificar esto para que el número de parte sea dinámico
        // ];

        // $view = view('label', $data)->render(); // Renderiza la vista con los datos

        // // Comandos IPL para la impresora
        // $iplCommands = '
        //     <STX>
        //     <ESC>C<FF>
        //     <ESC>P<FF>
        //     <ESC>Q1<FF>
        //     ' . $view . '
        //     <ETX>
        // ';

        // try {
        //     $connector = new WindowsPrintConnector("Intermec"); // Reemplaza "nombre_de_tu_impresora" con el nombre de tu impresora
        //     $printer = new Printer($connector);

        //     $printer->text($iplCommands); // Envía los comandos IPL a la impresora
        //     $printer->cut();
        //     $printer->close();

        //     // $printer->getPrintConnector()->write($iplCommands);
        //     // $printer->getPrintConnector()->finalize();

        //     return "Impresión exitosa"; // Opcionalmente, puedes devolver una respuesta para indicar que la impresión fue exitosa
        // } catch (\Exception $e) {
        //     return $e->getMessage(); // Devuelve un mensaje de error si ocurre alguna excepción
        // }
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
