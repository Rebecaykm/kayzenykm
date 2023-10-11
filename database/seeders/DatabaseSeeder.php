<?php

namespace Database\Seeders;

use App\Models\UnemploymentType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DepartamentSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            MeasurementSeeder::class,
            TypeSeeder::class,
            // ItemClassSeeder::class,
            // StandardPackageSeeder::class,
            // PlannerSeeder::class,
            ClientSeeder::class,
            ProjectSeeder::class,
            // WorkcenterSeeder::class,
            // PartNumberSeeder::class,
            TypeScrapSeeder::class,
            ScrapSeeder::class,
            UnemploymentTypeSeeder::class,
            UnemploymentSeeder::class,
            ShiftSeeder::class,
        ]);
    }
}
