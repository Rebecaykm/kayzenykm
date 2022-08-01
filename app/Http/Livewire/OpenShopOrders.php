<?php

namespace App\Http\Livewire;

use App\Models\Fso;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class OpenShopOrders extends Component
{
    use WithPagination;

    public $inputs = [];

    public function store($inputs)
    {
        dd($this->inputs);
    }

    /**
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        $open_orders = Fso::query()
            ->select(['SID', 'SWRKC', 'SDDTE', 'SORD', 'SPROD', 'SQREQ', 'SQFIN', 'SSTAT'])
            ->where('SID', '=', 'SO')
            ->whereNotIn('SSTAT', ['X', 'Y'])
            ->orderBy('SDDTE', 'DESC')
            ->simplePaginate(5);
        return view('livewire.open-shop-orders', ['open_orders' => $open_orders]);
    }
}
