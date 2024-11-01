<div>
    <div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Campo 1</th>
                    <th>Campo 2</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registros as $index => $registro)
                    <tr>
                        <td>
                            <input type="text" wire:model="registros.{{ $index }}.campo1" class="border p-1 w-full">
                        </td>
                        <td>
                            <input type="text" wire:model="registros.{{ $index }}.campo2" class="border p-1 w-full">
                        </td>
                        <td>
                            <button wire:click="actualizarFila({{ $index }})" class="bg-blue-500 text-white p-1 rounded">
                                Guardar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (session()->has('mensaje'))
            <div class="mt-4 text-green-500">
                {{ session('mensaje') }}
            </div>
        @endif
    </div>
</div>
