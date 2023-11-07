<?php

namespace App\Http\Controllers;

require  'C:\label\eps.php';

use App\Models\Client;
use BaconQrCode\Encoder\QrCode;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use EscposTest;
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
        echo now(); 
        // $random = sha1(base64_encode(sha1("Hello world")));
        // $random = $random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random.$random;



        // $connector = new WindowsPrintConnector("EPSON TM-T20 Receipt");
        // $printer = new Printer($connector);
        // $path = public_path("img".DIRECTORY_SEPARATOR."dashboard.png");
        // $ecpPostImage = EscposImage::load($path);
        // $printer->graphics($ecpPostImage);
        // $printer->cut();
        // $printer->close();


        // return response(    "success");

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
