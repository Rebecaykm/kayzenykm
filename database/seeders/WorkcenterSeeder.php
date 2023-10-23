<?php

namespace Database\Seeders;

use App\Models\Workcenter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkcenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Workcenter::factory(10)->create();
    }
}
