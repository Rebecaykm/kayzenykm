<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class OpenOrders extends Component
{

    public $sort = 'SDDTE';
    public $direction = 'DESC';

    public function render()
    {
        $openOrders = Order::query()
            ->select(['SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN'])
            ->orderBy($this->sort, $this->direction)
            ->simplePaginate(10);

        return view('livewire.open-orders', ['openOrders' => $openOrders]);
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'DESC') {
                $this->direction = 'ASC';
            } else {
                $this->direction = 'DESC';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'ASC';
        }
    }
}
