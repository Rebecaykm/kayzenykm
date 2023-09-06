<?php

namespace Database\Seeders;

use App\Models\StandardPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StandardPackage::factory(10)->create();
    }
}
