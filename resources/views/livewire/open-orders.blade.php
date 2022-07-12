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
                        <th class="px-4 py-3 text-center">Actions</th>
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
                                <div class="flex justify-center space-x-4 text-sm">
                                    <button @click="openModal"
                                        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-green-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray"
                                        aria-label="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Fin Tabla --}}

        {{-- Inicio Paginación --}}
        <div
            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
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
