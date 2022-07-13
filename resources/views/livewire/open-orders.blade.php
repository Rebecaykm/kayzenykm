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
                                    <button wire:click="$set('modal', true)" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-green-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadowwire:click="$set('modal', true)" aria-label="Edit">
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
    {{-- Inicio Modal --}}
    <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center">
        <!-- Modal -->
        <div wire:model="open" x-show="isModalOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 transform translate-y-1/2" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0  transform translate-y-1/2" @click.away="closeModal" @keydown.escape="closeModal" class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl" role="dialog" id="modal">
            <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
            <header class="flex justify-end">
                <button class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700" aria-label="close" @click="closeModal">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </button>
            </header>
            <!-- Modal body -->
            <div class="mt-4 mb-6">
                <!-- Modal title -->
                <p class="mb-4 text-lg font-semibold text-gray-700 dark:text-gray-300">
                    Open shop order
                </p>
                <!-- Modal description -->
                <div class="flex my-1">
                    <label class=" text-sm font-medium text-gray-900 dark:text-gray-300">Update open order date</label>
                </div>
                <div class="relative my-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input datepicker datepicker-autohide type="date" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500" placeholder="Select date">
                </div>
                <div class="flex my-4">
                    <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-green-600 bg-gray-100 rounded border-gray-300 focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cancel open order</label>
                </div>
            </div>
            <footer class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800">
                <button wire:click="$set('modal', false)" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg dark:text-gray-400 sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
                    Cancel
                </button>
                <button wire:click="$set('modal', false)" class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    Accept
                </button>
            </footer>
        </div>
    </div>
    {{-- Fin Modal --}}
</div>
