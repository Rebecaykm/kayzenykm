<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class powerbiController extends Controller
{
    public function HPO(Request $request)
    {

 return view('powerbi.HPO',[]);
    }
}
