<?php

namespace Database\Seeders;

use App\Models\Client;
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
        $a = Project::create(['type' => '1', 'model' => 'J36W', 'prefixe' => 'BJS - BPN - BRB - BAC- PED - BJV']);
        $b = Project::create(['type' => '2', 'model' => 'J03W', 'prefixe' => 'DA6 - DA7 - DA8 - P54 - DB1 - DD1 - DG7 - S51 - DGN']);
        $c = Project::create(['type' => '3', 'model' => 'J03G', 'prefixe' => 'DB7 - DD1 - DB7']);
        $d = Project::create(['type' => '4', 'model' => 'J59W', 'prefixe' => 'BDTS - BDTT - BDW - BDTV - BEK - BDWP - BDYS - BGV - BHY - BJE-BJD- PX1 - PEP ']);
        $e = Project::create(['type' => '5', 'model' => 'J59J', 'prefixe' => 'DGH - DGJ - DGK - DGL - DGY - DRV- PYY']);
        $f = Project::create(['type' => '7', 'model' => 'J34A', 'prefixe' => 'VA40 - BDTS70234 - BDTS56A9X']);
        $g = Project::create(['type' => '8', 'model' => '660B', 'prefixe' => '575 - 576 - 582 - 583']);
        $h = Project::create(['type' => '9', 'model' => 'J34H', 'prefixe' => 'VC67']);
        $i = Project::create(['type' => '10', 'model' => 'J34X', 'prefixe' => 'VC85']);
        $j = Project::create(['type' => '11', 'model' => '920B', 'prefixe' => '573 - 520']);
        $k = Project::create(['type' => '3Y', 'model' => '3Y', 'prefixe' => '104 (FG)']);
        $l = Project::create(['type' => '20', 'model' => 'J03N', 'prefixe' => 'DNJ - DHM - DDD - DA6 - PED']);

        $a->update(['client_id' => Client::where('code', '200000')->value('id')]);
        $b->update(['client_id' => Client::where('code', '200000')->value('id')]);
        $c->update(['client_id' => Client::where('code', '200000')->value('id')]);
        $d->update(['client_id' => Client::where('code', '200000')->value('id')]);
        $e->update(['client_id' => Client::where('code', '200000')->value('id')]);
        $f->update(['client_id' => Client::where('code', '200700')->value('id')]);
        $g->update(['client_id' => Client::where('code', '400403')->value('id')]);
        $h->update(['client_id' =>  Client::where('code', '200700')->value('id')]);
        $i->update(['client_id' =>  Client::where('code', '200700')->value('id')]);
        $j->update(['client_id' => Client::where('code', '400403')->value('id')]);
        $k->update(['client_id' => Client::where('code', '400501')->value('id')]);
        $l->update(['client_id' => Client::where('code', '200000')->value('id')]);
    }
}
