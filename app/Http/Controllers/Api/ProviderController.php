<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\YT1;
use App\Models\YT2;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function receiveProvider(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'min:20'],
            'created_at' => ['required']
        ]);

        $date = Carbon::parse($validated['created_at'])->format('d/m/Y');
        $time = Carbon::parse($validated['created_at'])->format('H:i:s');
        $code = $validated['code'];

        $type = substr($code, 0, 1);
        $orderNumber = substr($code, 1, 12);
        $secuenceNumber = substr($code, 13, 6);
        $snp = substr($code, 19, 6);

        try {
            $model = null;
            $updateFields = [
                'ORN' => $orderNumber,
                'SQN' => $secuenceNumber,
                'SNP' => $snp,
                'DAT' => $date,
                'TIM' => $time
            ];

            switch ($type) {
                case 1:
                    $model = new YT1();
                    $field = "Y1";

                    $updateFields = array_combine(array_map(fn($k) => 'Y1' . $k, array_keys($updateFields)), $updateFields);
                    break;
                case 2:
                    $model = new YT2();
                    $field = "Y2";
                    $updateFields = array_combine(array_map(fn($k) => 'Y2' . $k, array_keys($updateFields)), $updateFields);
                    break;
                default:
                    return response()->json(
                        ['error' => 'Invalid code.'],
                        400
                    );
            }

            $updateQuery = $model::query()
                ->where([
                    [$field . 'ORN', $orderNumber],
                    [$field . 'SQN', $secuenceNumber],
                    [$field . 'SNP', $snp]
                ])
                ->update($updateFields);

            if (!$updateQuery) {
                $model::insert($updateFields);
            }

            return response()->json(
                [
                    'message' => 'Code received successfully.',
                    'code' => $code
                ],
                200
            );
        } catch (QueryException $e) {
            return response()->json(
                [
                    'error' => 'Error processing the request: ' . $e->getMessage(),
                ],
                500
            );
        }
    }
}
