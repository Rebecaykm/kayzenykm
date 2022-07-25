<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class Search extends Component
{
    public $search;

    public function render()
    {
        // $users = User::where('name', 'LIKE', '%' . $this->search . '%')->orWhere('name', 'LIKE', '%' . $this->search . '%')->orderBy('id', 'DESC')->simplePaginate();

        $demos = Order::query()
            ->select(['SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])
            ->where('SDDTE', 'LIKE', '%' . $this->search . '%')
            ->orWhere('SORD', 'LIKE', '%' . $this->search . '%')
            ->orWhere('SWRKC', 'LIKE', '%' . $this->search . '%')
            ->orWhere('SPROD', 'LIKE', '%' . $this->search . '%')
            ->orderBy('SDDTE', 'DESC')
            ->simplePaginate(10);

        return view('livewire.search', ['demos' => $demos]);
    }
}
