<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="container grid px-6 mx-auto">
        <!--
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Tables
        </h2>
        -->
        <!-- With actions -->
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4>
        <div class="flex justify-end">
            <button wire:click.prevent="store()" type="submit" class="flex items-center justify-end p-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <span class="mx-2">Save</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </button>
        </div>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Work Center</th>
                        <th class="px-4 py-3">Due Date</th>
                        <th class="px-4 py-3">Shop Order Number</th>
                        <th class="px-4 py-3">Item Number</th>
                        <th class="px-4 py-3">Quantity Required</th>
                        <th class="px-4 py-3">Quantity Finished</th>
                        <th class="px-4 py-3">Data Change</th>
                        <th class="px-4 py-3">Cancel</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($open_orders as $open_order)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-xs">
                                <label>
                                    <input wire:model="inputs.{{ $open_order->SORD }}.swrkc" value="{{ $open_order->SWRKC }}" type="text" id="swrkc" name="swrkc" hidden/>
                                    {{ $open_order->SWRKC }}
                                </label>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <input wire:model="inputs.{{ $open_order->SORD }}.sddte" value="{{ $open_order->SDDTE }}" type="text" id="sddte" name="sddte" hidden/>
                                {{ $open_order->SDDTE }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <input wire:model="inputs.{{ $open_order->SORD }}.sord" value="{{ $open_order->SORD }}" type="text" id="sord" name="sord" hidden/>
                                {{ $open_order->SORD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <input wire:model="inputs.{{ $open_order->SORD }}.sprod" value="{{ $open_order->SPROD }}" type="text" id="sprod" name="sprod" hidden/>
                                {{ $open_order->SPROD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <input wire:model="inputs.{{ $open_order->SORD }}.sqreq" value="{{ $open_order->SQREQ }}" type="text" id="sqreq" name="sqreq" hidden/>
                                {{ $open_order->SQREQ }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <input wire:model="inputs.{{ $open_order->SORD }}.sqfin" value="{{ $open_order->SQFIN }}" type="text" id="sqfin" name="sqfin" hidden/>
                                {{ $open_order->SQFIN }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                <label class="block text-sm">
                                    <input wire:model="inputs.{{ $open_order->SORD }}.cdte" type="date" id="cdte" name="cdte" class="block w-full text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                                </label>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <label class="flex items-center justify-center dark:text-gray-400">
                                    <input wire:model="inputs.{{ $open_order->SORD }}.canc" type="checkbox" id="canc" name="canc" value="1" class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray"/>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Showing 21-30 of 100
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{ $open_orders->links() }}
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
    </div>
</div>
