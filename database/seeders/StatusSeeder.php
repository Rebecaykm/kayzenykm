<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create(['name' => 'PENDIENTE']);
        Status::create(['name' => 'EN PROCESO']);
        Status::create(['name' => 'FINALIZADO']);
        Status::create(['name' => 'CANCELADO']);
        Status::create(['name' => 'EN REVISIÓN']);
        Status::create(['name' => 'ACTIVO']);
        Status::create(['name' => 'INACTIVO']);
        Status::create(['name' => 'EN CURSO']);
        Status::create(['name' => 'DENTRO DE PLANEACIÓN']);
        Status::create(['name' => 'FUERA DE PLANEACIÓN']);
    }
}
