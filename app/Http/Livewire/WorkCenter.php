<?php

namespace App\Http\Livewire;

use App\Models\Departament;
use App\Models\LWK;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WorkCenter extends Component
{
    public $departament = '8';

    public function render()
    {
        $user = Auth::user();
        $arrayDepto = [];

        foreach ($user->departaments as $depto){
            array_push($arrayDepto, $depto->code);
        }

        $departaments = Departament::whereIn('code', $arrayDepto)->get();

        $workCenters = LWK::query()
            ->select('WWRKC', 'WDESC')
            ->orderBy('WWRKC', 'ASC')
            ->where('WWRKC', 'LIKE', $this->departament . '%')
            ->get();

        return view('livewire.work-center', ['workCenters' => $workCenters, 'departaments' => $departaments]);
    }
};
