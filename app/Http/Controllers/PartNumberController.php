<?php

namespace App\Http\Controllers;

use App\Models\PartNumber;
use App\Http\Requests\StorePartNumberRequest;
use App\Http\Requests\UpdatePartNumberRequest;
use App\Jobs\PartNumberMigrationJob;
use Illuminate\Http\Request;

class PartNumberController extends Controller
{
    /**
     *
     */
    function dataUpload()
    {
        PartNumberMigrationJob::dispatch();

        return redirect('part-number');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = strtoupper($request->search) ?? '';

        $partNumbers = PartNumber::query()->where('number', 'LIKE', '%' . $search . '%')->orderBy('updated_at', 'DESC')->paginate(10);

        return view('part-number.index', ['partNumbers' => $partNumbers]);
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
    public function store(StorePartNumberRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PartNumber $partNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PartNumber $partNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartNumberRequest $request, PartNumber $partNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PartNumber $partNumber)
    {
        //
    }

    function getPartNumberTree(Request $request)
    {
        $search = $request->search ?? '';

        $tree = [];

        if ($search != '') {
            $partNumbers = PartNumber::
            // whereHas('itemClass', function ($query) {
            //     $query->where('abbreviation', 'F1');
            // })
            //     ->
                where('number', 'LIKE', '%' . $search . '%')
                ->get();

            foreach ($partNumbers as $partNumber) {
                $processedNodes = [];
                $tree[] = $this->buildTree($partNumber, $processedNodes);
            }
        }

        return view('tree')->with('tree', $tree);
    }

    private function buildTree($partNumber, &$processedNodes)
    {
        // Verificar si el nodo ya ha sido procesado
        if (in_array($partNumber->id, $processedNodes)) {
            return null;
        }

        // Agregar el nodo actual al registro de nodos procesados
        $processedNodes[] = $partNumber->id;

        $treeNode = [
            // 'id' => $partNumber->id,
            'number' => $partNumber->number,
            'class' => $partNumber->itemClass->abbreviation,
            'required_quantiy' => $partNumber->pivot->required_quantity ?? '',
            'subParts' => [],
        ];

        $subPartNumbers = $partNumber->subPartNumbers;

        foreach ($subPartNumbers as $subPartNumber) {
            // Evitar que el subnúmero sea igual al número actual para prevenir ciclos infinitos
            if ($subPartNumber->id != $partNumber->id) {
                // echo "Número de parte: {$partNumber->name} - Subnúmero: {$subPartNumber->name}\n";

                $subTree = $this->buildTree($subPartNumber, $processedNodes);

                if ($subTree) {
                    $treeNode['subParts'][] = $subTree;
                }
            } else {
                // echo "Evitando ciclo infinito: {$partNumber->name} - Subnúmero igual al número actual: {$subPartNumber->name}\n";
            }
        }

        return $treeNode;
    }
}
