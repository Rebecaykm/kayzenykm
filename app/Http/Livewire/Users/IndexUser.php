<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class IndexUser extends Component
{

    protected $listeners = ['render' => 'render'];

    public $search;
    public $sort = 'id';
    public $direction = 'DESC';

    public function render()
    {
        $users = User::select('name', 'email', 'created_at', 'updated_at')
            ->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('email', 'LIKE', '%' . $this->search . '%')
            ->orWhere('created_at', 'LIKE', '%' . $this->search . '%')
            ->orWhere('updated_at', 'LIKE', '%' . $this->search . '%')
            ->orderBy($this->sort, $this->direction)
            ->paginate(10);

        return view('livewire.users.index-user', ['users' => $users]);
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
