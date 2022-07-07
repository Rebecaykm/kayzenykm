<div>
    <label>Buscar Usuario</label>
    <input wire:model="search" type="search">
    <ul>
        @foreach ($users as $user)
            <li>{{ $user->name }}</li>
        @endforeach
    </ul>
</div>
