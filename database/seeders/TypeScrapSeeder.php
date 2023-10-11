<?php

namespace Database\Seeders;

use App\Models\TypeScrap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeScrapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeScrap::create(['name' => 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO']);
        TypeScrap::create(['name' => 'DEFECTOS EN LA FUNCIONALIDAD DEL PRODUCTO']);
        TypeScrap::create(['name' => 'DEFECTOS EN EL ENSAMBLE']);
        TypeScrap::create(['name' => 'DEFECTOS EN LA PINTURA']);
        TypeScrap::create(['name' => 'DEFECTOS EN LA SOLDADURA']);
        TypeScrap::create(['name' => 'DEFECTOS VISUALES']);
        TypeScrap::create(['name' => 'DEFECTOS DE MATERIA PRIMA']);
        TypeScrap::create(['name' => 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL']);
    }
}
