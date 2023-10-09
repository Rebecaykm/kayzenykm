<?php

namespace Database\Seeders;

use App\Models\Unemployment;
use App\Models\UnemploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnemploymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unemployment::create(['name' => 'CHOREI DE INICIO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'BREAK 1RO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'BREAK 2DO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'COMEDOR', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'BOX LOUNCH', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'PRUEBAS DE INGENIERIA', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'SIMULACROS ', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'LIMPIEZA DE EQUIPOS', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'MANTENIMIETO PREVENTIVO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);
        Unemployment::create(['name' => 'JUNTA INFORMATIVA (MENSUAL)', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'PLANEADO')->pluck('id')->first()]);

        Unemployment::create(['name' => 'INSPECCION DE POKA YOKE', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CHEK LIST (INSPECCION DE EQUIPOS)', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'ETIQUETADO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'VALIDACION DE MATERIAL', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CAMBIO DE MODELO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CAMBIO CAPS', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CAMBIO DE ELECTRODOS', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CAMBIO DE MICRO ALAMBRE', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'NORMALES')->pluck('id')->first()]);

        Unemployment::create(['name' => 'FALTANTE DE MATERIAL', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'ESPERA DE MATERIAL', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'FALTANTE DE CONTENEDOR', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'ESPERA POR CONTENEDOR', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'IDAS AL BAÑO/ENFERMERIA/TOMAR AGUA', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'ESPERA POR INSTRUCCIONES DE LIDER', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'ESPERA POR LIBERACION DE ESTACION', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CAMPAÑA DE CALIDAD', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'JUNTAS DE RETRO ALIMENTACION', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'FALLAS DE SENSOR', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'FALLAS EN PROCESO (TEACHING)', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'FALLA EN CLAMP', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'FALLA EN LA PROGRAMACIÓN ', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'FALTA DE ETIQUETAS', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CAMBIO DE CONTENEDOR VACIO/LLENO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CAMBIO DE NAVAJAS', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'FALLA DE CONTROLADOR', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'TIP TAPADO', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'CALENTAMIENTO DE HOLDER', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
        Unemployment::create(['name' => 'BAJA RESISTENCIA', 'unemployment_type_id' => UnemploymentType::query()->where('name', 'ANORMALES')->pluck('id')->first()]);
    }
}
