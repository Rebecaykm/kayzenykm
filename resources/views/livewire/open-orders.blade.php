<div>
    <h2 class="my-4 px-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Open shop order report
    </h2>
    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        {{-- Inicio Tabla --}}
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th wire:click="order('SWRKC')" class="cursor-pointer px-4 py-3">Current Work Center</th>
                        <th wire:click="order('SDDTE')" class="cursor-pointer px-4 py-3">Due Date</th>
                        <th wire:click="order('SORD')" class="cursor-pointer px-4 py-3">Shop Order Number</th>
                        <th wire:click="order('SPROD')" class="cursor-pointer px-4 py-3">Item Number</th>
                        <th wire:click="order('SQREQ')" class="cursor-pointer px-4 py-3">Quantity Required</th>
                        <th wire:click="order('SQFIN')" class="cursor-pointer px-4 py-3">Quantity Finished</th>
                        <th class="px-4 py-3 text-center"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($openOrders as $openOrder)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-xs">
                                {{ $openOrder->SWRKC }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $openOrder->SDDTE }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $openOrder->SORD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $openOrder->SPROD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $openOrder->SQREQ }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $openOrder->SQFIN }}
                            </td>
                            <td class="px-4 py-3">
                                @livewire('edit-orders', ['open-orders' => $openOrder, key($openOrder->SORD)])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Fin Tabla --}}

        {{-- Inicio Paginación --}}
        <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-6">
                Y - TEC KEYLEX MÉXICO
            </span>
            <span class="flex col-span-3 mt-2 sm:mt-auto sm:justify-end">
                <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center">
                        {{ $openOrders->links() }}
                    </ul>
                </nav>
            </span>
        </div>
        {{-- Fin Paginacón --}}
    </div>
</div>
