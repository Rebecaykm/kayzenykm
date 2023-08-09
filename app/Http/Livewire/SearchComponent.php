<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LIM;

class SearchComponent extends Component
{
    //muestra la lista de sugerencias de la busqueda
    public $showlist = false;
    //dato a buscar
    public $search = "";
    //Almacena los datos para sugerencia
    public $results;
    //Almacena los datos a los que se hicieron click
    public $product;
    //Obtener registros en la busqueda
    public function searchProduct()
    {
        if (!empty($this->search)) {
            $this->results =  LIM::query()
            ->select('IPROD','ICLAS')
            ->where([['IPROD','LIKE','%'.$this->search.'%'],['ICLAS','F1'] ,['IID', '!=', 'IZ'],
            ['IMPLC', '!=', 'OBSOLETE'],['IPROD', 'Not like', '%-830%']])
            ->distinct('IPROD')
            ->get()->toArray();
            $this->showlist = true;

        } else {
            $this->showlist = false;

        }
    }
    public function getProduct($id=0){
        DD($id);
        $this->product = $id;
        $this->showlist = false;    
    }

    public function render()
    {
        return view('livewire.search-component');
    }
}
