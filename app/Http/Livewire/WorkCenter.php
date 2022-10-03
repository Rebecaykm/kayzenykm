<?php

namespace App\Http\Livewire;

use App\Models\Lwk;
use Livewire\Component;

class WorkCenter extends Component
{
    public $value;

    public function render()
    {

        $workCenters = Lwk::query()
            ->select('WWRKC', 'WDESC')
            ->orderBy('WWRKC', 'ASC')
            ->where('WWRKC', 'LIKE', $this->value . '%')
            ->get();

        return view('livewire.work-center', ['workCenters' => $workCenters]);
    }
}
