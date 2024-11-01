<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ItemFormComponent extends Component
{
    public $registros;

    protected $rules = [
        'registros.*.campo1' => 'required',  // Reemplaza "campo1" con el nombre real de tus campos
        'registros.*.campo2' => 'required',
    ];

    public function mount()
    {
        $this->registros = TuModelo::all()->toArray(); // Carga los registros como un array
    }

    public function actualizarFila($index)
    {
        $this->validate();

        $registro = TuModelo::find($this->registros[$index]['id']);
        $registro->update($this->registros[$index]);

        session()->flash('mensaje', 'Registro actualizado correctamente');
    }


    public function render()
    {
        return view('livewire.item-form-component');
    }
}
