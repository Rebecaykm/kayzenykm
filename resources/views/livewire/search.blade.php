<div>
    <label>Buscar Usuario</label>
    <input wire:model="search" type="search">

    <table>
        <thead>
            <tr>
                <th>CURRENT WORK CENTER</th>
                <th>DUE DATE</th>
                <th>SHOP ORDER NUMBER</th>
                <th>ITEM NUMBER</th>
                <th>QUANTITY REQUIRED</th>
                <th>QUANTITY FINISHED</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($demos as $demo)
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-xs text-center">
                        {{ $demo->SWRKC }}
                    </td>
                    <td>
                        {{ $demo->SDDTE }}
                    </td>
                    <td>
                        {{ $demo->SORD }}
                    </td>
                    <td>
                        {{ $demo->SPROD }}
                    </td>
                    <td>
                        {{ $demo->SQREQ }}
                    </td>
                    <td>
                        {{ $demo->SQFIN }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $demos->links() }}
</div>
