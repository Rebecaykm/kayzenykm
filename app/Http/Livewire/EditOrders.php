<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;

class EditOrders extends Component
{

    public $modal = false;
    public $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.edit-orders');
    }
}
