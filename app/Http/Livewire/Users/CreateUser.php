<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;

class CreateUser extends Component
{
    public $open = false;
    public $name, $emal, $password;

    public function render()
    {
        return view('livewire.users.create-user');
    }
}
