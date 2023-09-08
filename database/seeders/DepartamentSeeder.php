<?php

namespace Database\Seeders;

use App\Models\Departament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departament::create(['code' => '1100', 'name' => 'Estampado']);
        Departament::create(['code' => '1200', 'name' => 'Carrocería']);
        Departament::create(['code' => '1300', 'name' => 'Chasis']);
        Departament::create(['code' => '1400', 'name' => 'Pintura']);
        Departament::create(['code' => '4000', 'name' => 'Proveedor']);
    }
}
