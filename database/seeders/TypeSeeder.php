<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::create(['abbreviation' => 'F', 'name' => 'FinishedProd']);
        Type::create(['abbreviation' => 'G', 'name' => 'OutsideProd']);
        Type::create(['abbreviation' => 'M', 'name' => 'Manufactured']);
        Type::create(['abbreviation' => 'P', 'name' => 'Purchased']);
        Type::create(['abbreviation' => 'S', 'name' => 'Supply']);
        Type::create(['abbreviation' => 'T', 'name' => 'Materials']);
        Type::create(['abbreviation' => '0', 'name' => 'Phanthom']);
        Type::create(['abbreviation' => '3', 'name' => 'Assorment']);
        Type::create(['abbreviation' => '4', 'name' => 'Kit']);
        Type::create(['abbreviation' => '5', 'name' => 'Planning Bill']);
        Type::create(['abbreviation' => '6', 'name' => 'NON-INV']);
    }
}
