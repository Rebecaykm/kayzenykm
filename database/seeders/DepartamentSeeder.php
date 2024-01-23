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
        Departament::create(['code' => '20', 'name' => 'Mazda']);
        Departament::create(['code' => '11', 'name' => 'Estampado']);
        Departament::create(['code' => '12', 'name' => 'Carrocería']);
        Departament::create(['code' => '13', 'name' => 'Chasis']);
        Departament::create(['code' => '14', 'name' => 'Pintura']);
        Departament::create(['code' => '40', 'name' => 'Proveedor']);
    }
}
