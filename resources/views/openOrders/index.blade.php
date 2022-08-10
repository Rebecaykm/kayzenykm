<x-app-layout title="Open Orders">
    <div class="container grid px-6 mx-auto p-1">
        <h2 class="py-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Open shop order report
        </h2>
        <div class="p-2 w-full overflow-hidden rounded-lg shadow-xs">
            <form method="get" action="{{ route('open-orders.index') }}">
                <div class="flex flex-row grid grid-cols-4 items-center gap-1">
                    <div class="col-span-2 gap-1 ">
                        {{-- <div class="flex justify-center">
                            <div class="relative w-full max-w-xl focus-within:text-blue-500">
                                <div class="absolute inset-y-0 flex items-center pl-2">
                                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input id="search" name="search"
                                    class="w-full pl-8 pr-2 text-sm text-gray-700 placeholder-gray-300 bg-whit-100 border-0 rounded-md dark:placeholder-gray-300 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-200 focus:bg-white focus:border-blue-300 focus:outline-none focus:shadow-outline-blue form-input"
                                    type="text" placeholder="Search in open orders" aria-label="Search"
                                    autocomplete="off"/>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-span-2 flex flex-row justify-end gap-1">
                        <input type="date" id="due_date" name="due_date"
                               class="block w-full text-sm rounded-lg dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                               placeholder="Jane Doe"/>
                        <button type="submit"
                                class="flex items-center justify-end text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <span class="mx-2">Search</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="p-1 w-full overflow-hidden rounded-lg shadow-xs">
            <form method="post" action="{{ route('open-orders.store') }}">
                @csrf
                <div class="flex flex-row items-center justify-end">
                    <div class="flex flex-end">
                        <span class="flex m-2 sm:m-auto sm:justify-end sm:text-xs">
                            {{ $openOrders->withQueryString()->links() }}
                        </span>
                        <button type="submit"
                                class="flex items-center justify-end p-2 m-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <span class="mx-2">Update</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="w-full overflow-x-auto">
                    @if ($openOrders->count())
                        <table class="w-full whitespace-no-wrap rounded-lg">
                            <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-500 dark:bg-gray-800">
                                <th class="px-2 py-2">Work Center</th>
                                <th class="px-2 py-2">Due Date</th>
                                <th class="px-2 py-2">Shop Order Number</th>
                                <th class="px-2 py-2">Item Number</th>
                                <th class="px-2 py-2">Quantity Required</th>
                                <th class="px-2 py-2">Quantity Finished</th>
                                <th class="px-3 py-2">Data Change</th>
                                <th class="px-3 py-2">Cancel</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-800 dark:bg-gray-800">
                            @foreach ($openOrders as $openOrder)
                                <tr class="text-gray-700 dark:text-gray-400 overflow-y-auto">
                                    <td class="px-2 py-2 text-xs">
                                        <label class="flex items-center justify-center dark:text-gray-400">
                                            <input type="text"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][swrkc]" id="swrkc"
                                                   value="{{ $openOrder->SWRKC }}" hidden/>
                                            {{ $openOrder->SWRKC }}
                                        </label>
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        <label class="flex items-center justify-center dark:text-gray-400">
                                            <input type="text"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][sddte]" id="sddte"
                                                   value="{{ $openOrder->SDDTE }}" hidden/>
                                            {{ $openOrder->SDDTE }}
                                        </label>
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        <label class="flex items-center justify-center dark:text-gray-400">
                                            <input type="text"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][sord]" id="sord"
                                                   value="{{ $openOrder->SORD }}" hidden/>
                                            {{ $openOrder->SORD }}
                                        </label>
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        <label class="flex items-center justify-center dark:text-gray-400">
                                            <input type="text"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][sprod]"
                                                   id="sprod" value="{{ $openOrder->SPROD }}" hidden/>
                                            {{ $openOrder->SPROD }}
                                        </label>
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        <label class="flex items-center justify-center dark:text-gray-400">
                                            <input type="text"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][sqreq]"
                                                   id="sqreq" value="{{ $openOrder->SQREQ }}" hidden/>
                                            {{ $openOrder->SQREQ }}
                                        </label>
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        <label class="flex items-center justify-center dark:text-gray-400">
                                            <input type="text"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][sqfin]"
                                                   id="sqfin" value="{{ $openOrder->SQFIN }}" hidden/>
                                            {{ $openOrder->SQFIN }}
                                        </label>
                                    </td>
                                    <td class="px-2 py-2">
                                        <label class="block text-sm">
                                            <input id="cdte"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][cdte]"
                                                   type="date"
                                                   class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                                        </label>
                                    </td>
                                    <td class="px-2 py-2">
                                        <label class="flex items-center justify-center dark:text-gray-400">
                                            <input id="canc"
                                                   name="arrayOpenOrders[{{ $openOrder->SORD }}][canc]"
                                                   type="checkbox" value="1"
                                                   class="text-blue-600 form-checkbox focus:border-blue-800 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray"/>
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div
                            class="px-4 py-3 rounded-md text-sm font-semibold text-gray-700 uppercase bg-gray-100 sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                            Open orders not found
                        </div>
                    @endif
                </div>
            </form>
            <div
                class="grid px-4 py-3 text-xs rounded-md font-semibold tracking-wide text-gray-700 uppercase border-t dark:border-gray-700 bg-gray-100 sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Show {{ $openOrders->firstItem() }} - {{ $openOrders->lastItem() }} of {{ $totalOrder }}
                </span>
                <!-- Pagination -->
                <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                    @if ($openOrders->count())
                        {{ $openOrders->withQueryString()->links() }}
                    @endif
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
