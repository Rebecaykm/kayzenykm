<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\IIM;
class AddressAutocomplete extends Component
{
    public string $streetAddress = '';
    public string $city = '';
    public string $state = '';
    public string $zip = '';
    public string $country = '';
    public array $searchResults = [];

    // Magic method that is fired when `streetAddress` is updated
    public function updatedStreetAddress()
    {
        if($this->streetAddress != '') {
            // An array of SearchResults
            $this->searchResults =
            IIM::query()
            ->select('IPROD','ICLAS')
            ->where([['IPROD','LIKE','%BDT%'],['ICLAS','F1'] ,['IID', '!=', 'IZ'],
            ['IMPLC', '!=', 'OBSOLETE'],['IPROD', 'Not like', '%-830%']])
            ->distinct('IPROD')
            ->get()->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function render()
    {
        return view('livewire.address-autocomplete');
    }
}
