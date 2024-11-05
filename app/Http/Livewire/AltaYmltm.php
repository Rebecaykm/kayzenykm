<?php

namespace App\Http\Livewire;

use App\Models\YMLTM;
use Livewire\Component;

class AltaYmltm extends Component
{
    public $item;

    public $cantidad;

    protected $rules = [
        'item' => 'required|string|max:255',
        'cantidad' => 'required|integer|min:0|max:10',
    ];

    public function mount($parte)
    {
         $this->item =  $parte;
    }
    public function guardar()
    {
        // dd( $this->item,$this->cantidad);

        // YMLTM::create([
        //     'LTPROD' => $this->item,
        //     'LTLDTM' => $this->cantidad,
        // ]);

        // Reiniciar los campos después de guardar


        // session()->flash('mensaje', 'Registro agregado exitosamente.');
    }


    public function render()
    {
        return view('livewire.alta-ymltm');
    }
}
