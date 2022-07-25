<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $start = $request->start;
        // $end = $request->end;


        // if (is_null($request->start) || is_null($request->end)) {
        //     $orders = Order::query()
        //         ->select(['SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])
        //         ->orderBy('SDDTE', 'DESC')
        //         ->simplePaginate(15);
        // } else {

        //     $orders = Order::query()
        //         ->select(['SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])
        //         ->whereBetween(
        //             'SDDTE',
        //             [
        //                 Carbon::parse($start)->format('Ymd'),
        //                 Carbon::parse($end)->format('Ymd')
        //             ]
        //         )
        //         ->orderBy('SDDTE', 'DESC')
        //         ->simplePaginate(15);
        // }

        $orders = Order::query()
                ->select(['SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])
                ->orderBy('SDDTE', 'DESC')
                ->simplePaginate(15);

        return view('order', ['orders' => $orders]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
