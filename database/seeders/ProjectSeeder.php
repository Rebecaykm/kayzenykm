<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create(['type' => '1', 'model' => 'J36W', 'prefixe' => 'BJS - BPN - BRB - BAC- PED - BJV']);
        Project::create(['type' => '2', 'model' => 'J03W', 'prefixe' => 'DA6 - DA7 - DA8 - P54 - DB1 - DD1 - DG7 - S51 - DGN']);
        Project::create(['type' => '3', 'model' => 'J03G', 'prefixe' => 'DB7 - DD1 - DB7']);
        Project::create(['type' => '4', 'model' => 'J59W', 'prefixe' => 'BDTS - BDTT - BDW - BDTV - BEK - BDWP - BDYS - BGV - BHY - BJE-BJD- PX1 - PEP ']);
        Project::create(['type' => '5', 'model' => 'J59J', 'prefixe' => 'DGH - DGJ - DGK - DGL - DGY - DRV- PYY']);
        Project::create(['type' => '7', 'model' => 'J34A', 'prefixe' => 'VA40 - BDTS70234 - BDTS56A9X']);
        Project::create(['type' => '8', 'model' => '660B', 'prefixe' => '575 - 576 - 582 - 583']);
        Project::create(['type' => '9', 'model' => 'J34H', 'prefixe' => 'VC67']);
        Project::create(['type' => '10', 'model' => 'J34X', 'prefixe' => 'VC85']);
        Project::create(['type' => '11', 'model' => '920B', 'prefixe' => '573 - 520']);
        Project::create(['type' => '3Y', 'model' => '3Y', 'prefixe' => '104 (FG)']);
        Project::create(['type' => '20', 'model' => 'J03N', 'prefixe' => 'DNJ - DHM - DDD - DA6 - PED']);
    }
}
