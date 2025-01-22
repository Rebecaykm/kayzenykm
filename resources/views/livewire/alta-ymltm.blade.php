<div>
    @if (session()->has('mensaje'))
    <div class="text-green-500 mb-4">
        {{ session('mensaje') }}
    </div>
@endif

<form wire:submit.prevent="guardar">
    @csrf
    <div class="mb-4">
        <label for="cantidad" class="block font-semibold">Desplazamiento por turno</label>
        <input type="hidden" value='{{ $item }}' id="item" wire:model="item" class="border p-2 w-full">
        <input type="number" id="cantidad" wire:model="cantidad" class="border p-2 w-full" required>

    </div>
    <button type="submit" class="bg-blue-500 text-white p-2 rounded">Guardar</button>
</form>
</div>
