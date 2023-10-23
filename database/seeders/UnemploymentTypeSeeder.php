<?php

namespace Database\Seeders;

use App\Models\UnemploymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnemploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnemploymentType::create(['name' => 'PLANEADO']);
        UnemploymentType::create(['name' => 'NORMALES']);
        UnemploymentType::create(['name' => 'ANORMALES']);
    }
}
