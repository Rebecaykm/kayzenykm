<?php

namespace App\Http\Controllers;

use App\Models\YHSCR;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScrapRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = YHSCR::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('SCPROD', 'like', "%{$search}%");
        }

        $scrapRateProducedParts = $query->orderBy('SCPROD', 'desc')->simplePaginate(10);

        return view('scrap-rate.index', compact('scrapRateProducedParts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $formattedStartDate = Carbon::createFromFormat('Ymd', $request->startDate)->format('Y-m-d');

        return view('scrap-rate.edit', [
            'partNumber' => $request->partNumber,
            'startDate' => $formattedStartDate,
            'scrapRate' => $request->scrapRate,
            'createdDate' => $request->createdDate,
            'createdTime' => $request->createdTime,
            'createdUser' => $request->createdUser,
            'createdWs' => $request->createdWs
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $scrapRateDecimal = $request->scrapRatePercent / 100;
            $startDate = Carbon::parse($request->startDate)->format('Ymd');

            $record  = YHSCR::query()
                ->where(DB::raw('TRIM(SCPROD)'), $request->partNumber)
                ->where(DB::raw('TRIM(SCCRDT)'), 'LIKE', $request->createdDate)
                ->where(DB::raw('TRIM(SCCRTM)'), 'LIKE', $request->createdTime)
                ->where(DB::raw('TRIM(SCCRUS)'), 'LIKE', $request->createdUser)
                ->where(DB::raw('TRIM(SCCRWS)'), 'LIKE', $request->createdWs)
                ->update([
                    'SCRATE' => $scrapRateDecimal,
                    'SCSTDT' => $startDate
                ]);

            if (!$record) {
                return back()->with('error', 'Registro no encontrado.');
            }

            if ($record) {
                return redirect()->route('scrap-rate.index')
                    ->with('success', 'La tasa de scrap se ha actualizado correctamente.');
            }

            return back()->with('error', 'No se pudo actualizar el registro.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
