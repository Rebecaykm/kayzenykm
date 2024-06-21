<?php

namespace App\Imports;

use App\Models\Line;
use App\Models\Unemployment;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnemploymentUpdateImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $unemployment = Unemployment::where('name', $row['name'])->first();

        if ($unemployment) {
            // Obtener líneas del campo 'line' separadas por comas
            $lineNames = explode(',', $row['line']);

            $lineIds = [];

            foreach ($lineNames as $lineName) {
                // Limpiar el nombre de la línea (eliminar espacios en blanco)
                $cleanLineName = trim($lineName);

                // Buscar la línea existente por nombre
                $line = Line::where('name', $cleanLineName)->first();

                if ($line) {
                    $lineIds[] = $line->id;
                } else {
                    // Loggear el error si la línea no existe
                    Log::error("No se encontró la línea con el nombre: '{$cleanLineName}' para el Unemployment '{$unemployment->name}'");
                }
            }

            // Sincronizar las relaciones con las líneas encontradas
            $unemployment->lines()->sync($lineIds);
        }

        return null;
    }
}
