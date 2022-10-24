<?php

namespace App\Http\Livewire;

use App\Models\Lwk;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WorkCenter extends Component
{
    public function render()
    {
        $user = Auth::user();
        $estampado = '19';
        $carroceria   = '19';
        $chasis = '19';
        $pintura = '19';
        $proveedor = '19';

        foreach ($user->departaments as $departament) {
            switch ($departament->code) {
                case '11':
                    $estampado = '11';
                    break;
                case '12':
                    $carroceria = '12';
                    break;
                case '13':
                    $chasis = '13';
                    break;
                case '14':
                    $pintura = '14';
                    break;
                case '40':
                    $proveedor = '40';
                    break;
            }
        }

        $workCenters = Lwk::query()
            ->select('WWRKC', 'WDESC')
            ->orderBy('WWRKC', 'ASC')
            ->where('WWRKC', 'LIKE', $estampado . '%')
            ->orWhere('WWRKC', 'LIKE', $carroceria . '%')
            ->orWhere('WWRKC', 'LIKE', $chasis . '%')
            ->orWhere('WWRKC', 'LIKE', $pintura . '%')
            ->orWhere('WWRKC', 'LIKE', $proveedor . '%')
            ->get();

        return view('livewire.work-center', ['workCenters' => $workCenters]);
    }
};
