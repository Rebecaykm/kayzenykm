<?php

namespace App\Imports;

use App\Models\Unemployment;
use App\Models\UnemploymentType;
use App\Models\Line;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnemploymentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buscar o crear el UnemploymentType basado en el nombre
        $unemploymentType = UnemploymentType::firstOrCreate(
            ['name' => $row['type']]
        );

        // Buscar el Unemployment basado en code y name
        $unemployment = Unemployment::where('code', $row['code'])
            ->where('name', $row['name'])
            ->first();

        if ($unemployment) {
            // Si se encuentra, actualizar unemployment_type_id si es necesario
            if ($unemployment->unemployment_type_id != $unemploymentType->id) {
                $unemployment->unemployment_type_id = $unemploymentType->id;
                $unemployment->save();
            }

            // Actualizar las relaciones de líneas
            $lineNames = explode(',', $row['line']);
            $lineIds = [];

            foreach ($lineNames as $lineName) {
                // Buscar las líneas por nombre
                $line = Line::where('name', trim($lineName))->first();

                if ($line) {
                    $lineIds[] = $line->id;
                } else {
                    // Registrar un log si la línea no se encuentra
                    Log::warning("Línea no encontrada: " . trim($lineName));
                }
            }

            // Adjuntar las líneas encontradas sin eliminar las relaciones existentes
            if (!empty($lineIds)) {
                $unemployment->lines()->syncWithoutDetaching($lineIds);
            }
        } else {
            // Si no se encuentra, crear uno nuevo
            $unemployment = Unemployment::create([
                'code' => $row['code'],
                'name' => $row['name'],
                'unemployment_type_id' => $unemploymentType->id,
            ]);

            // Manejar la relación muchos a muchos con Line
            $lineNames = explode(',', $row['line']);
            $lineIds = [];

            foreach ($lineNames as $lineName) {
                // Buscar las líneas por nombre
                $line = Line::where('name', trim($lineName))->first();

                if ($line) {
                    $lineIds[] = $line->id;
                } else {
                    // Registrar un log si la línea no se encuentra
                    Log::warning("Línea no encontrada: " . trim($lineName));
                }
            }

            // Adjuntar las líneas encontradas
            if (!empty($lineIds)) {
                $unemployment->lines()->attach($lineIds);
            }
        }

        return $unemployment;
    }
}
