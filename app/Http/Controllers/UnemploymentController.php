<?php

namespace App\Http\Controllers;

use App\Models\Unemployment;
use App\Http\Requests\StoreUnemploymentRequest;
use App\Http\Requests\UpdateUnemploymentRequest;
use App\Imports\UnemploymentImport;
use App\Models\UnemploymentType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UnemploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unemployments = Unemployment::query()->orderBy('name', 'ASC')->paginate(10);

        return view('unemployment.index', ['unemployments' => $unemployments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unemploymentTypes = UnemploymentType::query()->orderBy('name', 'DESC')->get();

        return view('unemployment.create', ['unemploymentTypes' => $unemploymentTypes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnemploymentRequest $request)
    {
        $unemployment =  Unemployment::create([
            'abbreviation' => $request->abbreviation,
            'name' => $request->name,
            'unemployment_type_id' => $request->unemployment_type_id ?? null
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Unemployment $unemployment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unemployment $unemployment)
    {
        $unemploymentTypes = UnemploymentType::query()->orderBy('name', 'DESC')->get();

        return view('unemployment.edit', ['unemployment' => $unemployment, 'unemploymentTypes' => $unemploymentTypes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnemploymentRequest $request, Unemployment $unemployment)
    {
        $unemployment->fill($request->validated());

        if ($unemployment->isDirty()) {
            $unemployment->save();
        }

        return redirect()->route('unemployment.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unemployment $unemployment)
    {
        $unemployment->delete();

        return redirect()->back();
    }

    public function viewFile()
    {
        return view('unemployment.import');
    }

    /**
     *
     */
    public function importFile(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        try {
            $file = $request->file('file');

            Excel::import(new UnemploymentImport, $file);

            return back()->with('success', 'Importación completada con éxito.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            Log::error('Error de validación durante la importación: ' . $e->getMessage());

            $errorMessages = '';
            foreach ($failures as $failure) {
                $errorMessages .= 'Fila ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '<br>';
            }

            return back()->with('error', 'Errores de validación encontrados: <br>' . $errorMessages);
        } catch (Exception $e) {
            Log::error('Error inesperado durante la importación: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error inesperado durante la importación. Por favor, inténtalo de nuevo.');
        }
    }
}
