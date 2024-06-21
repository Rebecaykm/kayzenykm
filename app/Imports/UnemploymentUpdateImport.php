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
        $unemployment = Unemployment::where('name', 'LIKE', '%' . $row['name'] . '%')->first();

        if ($unemployment) {
            // Obtener líneas del campo 'line' separadas por comas
            $lineNames = explode(',', $row['line']);

            foreach ($lineNames as $lineName) {
                // Limpiar el nombre de la línea (eliminar espacios en blanco)
                $cleanLineName = trim($lineName);

                // Buscar la línea existente por nombre
                $line = Line::where('name', $cleanLineName)->first();

                // Si la línea existe, relacionarla con el Unemployment
                if ($line) {
                    // Verificar si ya está relacionada
                    if (!$unemployment->lines()->where('line_id', $line->id)->exists()) {
                        $unemployment->lines()->attach($line);
                    }
                } else {
                    // Loggear el error si la línea no existe
                    Log::error("No se encontró la línea con el nombre: '{$cleanLineName}' para el Unemployment '{$unemployment->name}'");
                }
            }
        }

        return null;
    }
}
