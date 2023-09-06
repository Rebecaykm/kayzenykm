<?php

namespace Database\Seeders;

use App\Models\Planner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Planner::factory(10)->create();
    }
}
