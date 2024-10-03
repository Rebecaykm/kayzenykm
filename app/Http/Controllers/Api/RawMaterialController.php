<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RYT4;
use App\Models\YHMIC;
use App\Models\YT4;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $date = Carbon::parse($request['created_at'])->format('Ymd');
            $time = Carbon::parse($request['created_at'])->format('His');
            $code = strtoupper(trim(strval($request->code)));

            if (strlen($code) >= 7 && strlen($code) <= 15) {

                $query = YHMIC::query()->where('YIPCNO', 'LIKE', $code . '%')->get();

                foreach ($query as $key => $row) {

                    $yt4 = YT4::where([
                        ['Y4SINO', 'LIKE', $row->YISINO . '%'],
                        ['Y4TINO', 'LIKE', $row->YIPCNO . '%'],
                        ['Y4TQTY', 'LIKE', $row->YIPQTY . '%'],
                        ['Y4PROD', 'LIKE', $row->YIPROD . '%'],
                        ['Y4ORDN', 'LIKE', $row->YIORDN . '%']
                    ])->first();

                    $ryt4 = RYT4::where([
                        ['R4SINO', 'LIKE', $row->YISINO . '%'],
                        ['R4TINO', 'LIKE', $row->YIPCNO . '%'],
                        ['R4TQTY', 'LIKE', $row->YIPQTY . '%'],
                        ['R4PROD', 'LIKE', $row->YIPROD . '%'],
                        ['R4ORDN', 'LIKE', $row->YIORDN . '%']
                    ])->first();

                    if (is_null($yt4) && is_null($ryt4)) {
                        YT4::query()->insert([
                            'Y4SINO' => $row->YISINO,
                            'Y4TINO' => $row->YIPCNO,
                            'Y4TQTY' => $row->YIPQTY,
                            'Y4PROD' => $row->YIPROD,
                            'Y4ORDN' => $row->YIORDN,
                            'Y4TORD' => $row->YITORD,
                            'Y4DAT' => $date,
                            'Y4TIM' => $time,
                        ]);
                    }
                }

                return response()->json(
                    [
                        'message' => 'Code received successfully.',
                        'code' => $code
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'message' => 'Invalid code.',
                        'code' => $code
                    ],
                    400
                );
            }
        } catch (QueryException $e) {
            return response()->json(
                [
                    'error' => 'Error processing the request: ' . $e->getMessage(),
                ],
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
