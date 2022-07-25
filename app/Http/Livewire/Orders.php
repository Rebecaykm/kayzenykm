<?php

namespace App\Http\Livewire;

use App\Models\Fso;
use App\Models\FsoLocal;
use Carbon\Carbon;
use Livewire\Component;

class Orders extends Component
{
    public $SID, $SWRKC, $SDDTE, $SORD, $SPROD, $SQREQ, $SQFIN, $CDTE, $CANC;

    public function save()
    {
        FsoLocal::create([
            'SID' => $this->SID,
            'SWRKC' => $this->SWRKC,
            'SDDTE' => $this->SDDTE,
            'SORD' => $this->SORD,
            'SPROD' => $this->SPROD,
            'SQREQ' => $this->SQREQ,
            'SQFIN' => $this->SQFIN,
            'CDTE' => $this->CDTE,
            'CANC' => $this->CANC,
        ]);
    }

    public function render()
    {
        $orders = Fso::query()
            ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])
            ->orderBy('SDDTE', 'DESC')
            ->where('SID', '=', 'SO')
            ->simplePaginate(10);

        return view('livewire.orders', ['orders' => $orders]);
    }
}
