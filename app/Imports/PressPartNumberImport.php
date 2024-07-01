<?php

namespace App\Imports;

use App\Models\PressPartNumber;
use App\Models\PartNumber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PressPartNumberImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return PressPartNumber|null
     */
    public function model(array $row)
    {
        // Iniciar una transacción
        DB::beginTransaction();

        try {
            $ppn = str_replace(' ', '', strtoupper(trim($row['part_number'])));

            // Buscar o crear el PressPartNumber
            $pressPartNumber = PressPartNumber::firstOrCreate(
                ['part_number' => $ppn],
                ['pieces_per_hit' => $row['piezas_golpe']]
            );

            // Procesar los números de parte relacionados
            $partNumbers = $this->processPartNumber($row['part_number']);

            foreach ($partNumbers as $key => $partNumber) {
                // Buscar el PartNumber en la base de datos
                $partNumberModel = PartNumber::where(DB::raw('REPLACE(number, \' \', \'\')'), 'LIKE', $partNumber)->first();

                if ($partNumberModel) {
                    // Adjuntar el PartNumber encontrado al PressPartNumber si no existe la relación
                    if (!$pressPartNumber->partNumbers()->where('part_number_id', $partNumberModel->id)->exists()) {
                        $pressPartNumber->partNumbers()->attach([$partNumberModel->id]);
                    }
                } else {
                    Log::warning("Número de parte no encontrado: " . $partNumber);
                }
            }

            // Confirmar la transacción
            DB::commit();

            // Devolver el PressPartNumber creado o encontrado
            return $pressPartNumber;
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollback();
            Log::error("Error al importar PressPartNumber: " . $e->getMessage());

            // Retornar null para indicar que hubo un error
            return null;
        }
    }

    private function processPartNumber($partNumber)
    {
        // Si no contiene "/", solo quita los espacios y devuélvelo
        if (strpos($partNumber, '/') === false) {
            return [str_replace(' ', '', $partNumber)];
        }

        // Separar por espacios
        $parts = explode(' ', $partNumber);

        // Base
        $base = $parts[0];

        // Procesar cada parte y concatenar
        $combinations = [$base];

        for ($i = 1; $i < count($parts); $i++) {
            $newCombinations = [];
            $subParts = explode('/', $parts[$i]);

            foreach ($combinations as $combination) {
                foreach ($subParts as $subPart) {
                    $newCombinations[] = $combination . $subPart;
                }
            }

            $combinations = $newCombinations;
        }

        return $combinations;
    }
}
