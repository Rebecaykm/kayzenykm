<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class CreateUser extends Component
{
    public $openModal = false;
    public $name, $email, $password;

    public function save()
    {
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $this->reset(['openModal', 'name', 'email', 'password']);
        $this->emit(['render']);
    }

    public function render()
    {
        return view('livewire.users.create-user');
    }
}
