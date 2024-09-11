<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Http\Requests\StorePrinterRequest;
use App\Http\Requests\UpdatePrinterRequest;
use App\Models\Departament;
use App\Models\Workcenter;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrinterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $printers = Printer::orderBy('brand')->orderBy('ip')->paginate(10);

        return view('printer.index', ['printers' => $printers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $workcenters = Workcenter::where(function ($query) use ($departamentCode) {
            foreach ($departamentCode as $code) {
                $query->orWhere('number', 'LIKE', $code . '%');
            }
        })
            ->orderBy('name', 'asc')
            ->get();

        return view('printer.create', ['workcenters' => $workcenters]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrinterRequest $request)
    {
        try {
            $printer = Printer::create([
                'brand' => $request->brand,
                'model' => $request->model,
                'ip' => $request->ip,
                'port' => $request->port,
                'description' => $request->description,
            ]);

            foreach ($request->workcenters as $workcenterId) {
                $workcenter = Workcenter::find($workcenterId);
                if ($workcenter) {
                    $printer->workcenters()->save($workcenter);
                }
            }

            return redirect()->back()->with('success', 'Se registró con éxito la impresora');
        } catch (QueryException $exception) {
            Log::error('Error al intentar registrar la línea: ' . $exception->getMessage());

            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar registrar la impresora');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Printer $printer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Printer $printer)
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $workcenters = Workcenter::where(function ($query) use ($departamentCode) {
            foreach ($departamentCode as $code) {
                $query->orWhere('number', 'LIKE', $code . '%');
            }
        })
            ->orderBy('name', 'asc')
            ->get();

        return view('printer.edit', ['printer' => $printer, 'workcenters' => $workcenters]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrinterRequest $request, Printer $printer)
    {
        try {
            $data = $request->except('workcenters');

            $printer->fill($data);

            if ($printer->isDirty()) {
                $printer->save();
            }

            $printer->load('workcenters');

            if ($printer->workcenters) {
                foreach ($printer->workcenters as $workcenter) {
                    $workcenter->update(['printer_id' => null]);
                }
            }

            if ($request->workcenters) {
                foreach ($request->workcenters as $workcenterId) {
                    $workcenter = Workcenter::find($workcenterId);
                    if ($workcenter) {
                        $printer->workcenters()->save($workcenter);
                    }
                }
            }

            return redirect()->back()->with('success', 'Se actualizó con éxito la impresora');
        } catch (QueryException $exception) {
            Log::error('Error al intentar actualizar la línea: ' . $exception->getMessage());

            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar actualizar la impresora');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Printer $printer)
    {
        try {
            $printer->delete();
            return redirect()->back()->with('success', 'La línea se eliminó correctamente.');
        } catch (QueryException $exception) {
            Log::error('Error al eliminar la línea: ' . $exception->getMessage());

            return redirect()->back()->with('error', 'No se puede eliminar la línea debido a restricciones de integridad referencial.');
        }
    }
}
