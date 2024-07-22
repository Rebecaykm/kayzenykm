<?php

namespace App\Http\Controllers;

use App\Imports\PressPartNumberImport;
use App\Models\PressPartNumber;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PressPartNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $pressPartNumbers = PressPartNumber::query()->where('part_number', 'LIKE', '%' . $search . '%')->orderBy('part_number', 'ASC')->paginate(10);

        return view('pressPartNumber.index', ['pressPartNumbers' => $pressPartNumbers]);
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
    public function edit(string $id)
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

    /**
     *
     */
    public function viewFile()
    {
        return view('pressPartNumber.import');
    }

    /**
     *
     */
    public function importFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        // try {
            $file = $request->file('file');

            Excel::import(new PressPartNumberImport, $file);

            return back()->with('success', 'Importación completada con éxito.');
        // } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        //     $failures = $e->failures();
        //     Log::error('Error de validación durante la importación: ' . $e->getMessage());

        //     $errorMessages = '';
        //     foreach ($failures as $failure) {
        //         $errorMessages .= 'Fila ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '<br>';
        //     }

        //     return back()->with('error', 'Errores de validación encontrados: <br>' . $errorMessages);
        // } catch (Exception $e) {
        //     Log::error('Error inesperado durante la importación: ' . $e->getMessage());
        //     return back()->with('error', 'Ocurrió un error inesperado durante la importación. Por favor, inténtalo de nuevo.');
        // }
    }
}
