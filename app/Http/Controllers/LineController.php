<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLineRequest;
use App\Http\Requests\UpdateLineRequest;
use App\Models\Departament;
use App\Models\Line;
use App\Models\Workcenter;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departamentId = Auth::user()->departaments->pluck('id')->toArray();

        $lines = Line::whereIn('departament_id', $departamentId)
            ->orderBy('departament_id', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('line.index', ['lines' => $lines]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $departaments = Departament::whereIn('code', $departamentCode)->get();

        $workcenters = Workcenter::where(function ($query) use ($departamentCode) {
            foreach ($departamentCode as $code) {
                $query->orWhere('number', 'LIKE', $code . '%');
            }
        })
            ->orderBy('number', 'asc')
            ->get();

        return view('line.create', ['workcenters' => $workcenters, 'departaments' => $departaments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLineRequest $request)
    {
        try {
            $line = Line::create([
                'name' => $request->name,
                'departament_id' => $request->departament_id
            ]);

            foreach ($request->workcenters as $workcenterId) {
                $workcenter = Workcenter::find($workcenterId);
                if ($workcenter) {
                    $line->workcenters()->save($workcenter);
                }
            }

            return redirect()->back()->with('success', 'Se registró con éxito la línea');
        } catch (QueryException $exception) {
            Log::error('Error al intentar registrar la línea: ' . $exception->getMessage());

            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar registrar la línea');
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
     * Show the form for editing the specified resource.
     */
    public function edit(Line $line)
    {
        $departamentCode = Auth::user()->departaments->pluck('code')->toArray();

        $departaments = Departament::whereIn('code', $departamentCode)->get();

        $workcenters = Workcenter::where(function ($query) use ($departamentCode) {
            foreach ($departamentCode as $code) {
                $query->orWhere('number', 'LIKE', $code . '%');
            }
        })
            ->orderBy('number', 'asc')
            ->get();

        return view('line.edit', ['line' => $line, 'workcenters' => $workcenters, 'departaments' => $departaments]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLineRequest $request, Line $line)
    {
        try {
            $data = $request->except('workcenters');

            $line->fill($data);

            if ($line->isDirty()) {
                $line->save();
            }

            $line->load('workcenters');

            if ($line->workcenters) {
                foreach ($line->workcenters as $workcenter) {
                    $workcenter->update(['line_id' => null]);
                }
            }

            if ($request->workcenters) {
                foreach ($request->workcenters as $workcenterId) {
                    $workcenter = Workcenter::find($workcenterId);
                    if ($workcenter) {
                        $line->workcenters()->save($workcenter);
                    }
                }
            }

            return redirect()->back()->with('success', 'Se actualizó con éxito la línea');
        } catch (QueryException $exception) {
            Log::error('Error al intentar actualizar la línea: ' . $exception->getMessage());

            return redirect()->back()->with('error', 'Ha ocurrido un error al intentar actualizar la línea');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Line $line)
    {
        try {
            $line->delete();
            return redirect()->back()->with('success', 'La línea se eliminó correctamente.');
        } catch (QueryException $exception) {
            Log::error('Error al eliminar la línea: ' . $exception->getMessage());

            return redirect()->back()->with('error', 'No se puede eliminar la línea debido a restricciones de integridad referencial.');
        }
    }
}
