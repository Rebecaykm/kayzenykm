<?php

namespace Database\Seeders;

use App\Models\Unemployment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnemploymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unemployment::factory()->create();
    }
}
