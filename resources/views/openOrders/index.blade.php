<x-app-layout title="Open Orders">
    <div class="container grid px-6 mx-auto">
        <!--
        <h2 class="my-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Open shop order report
        </h2>
        -->
        <!-- With actions -->
        <!--
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4>
        -->
        <form method="get" action="{{ route('open-orders.index') }}">
            <div class="flex flex-row grid grid-cols-4 items-center my-2">
                <div class="col-span-2">

                </div>
                <div class="col-span-2 flex flex-row justify-end">
                    <input type="date" id="due_date" name="due_date" class="block w-64 m-1 text-sm rounded-lg dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe"/>
                    <button type="submit" class="px-4 py-2 m-1 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                        Search by Due Date
                    </button>
                </div>
            </div>
        </form>

        <div class="w-full overflow-hidden rounded-lg shadow-xs px-4 py-3 mb-8 bg-white shadow-md dark:bg-gray-800">
            <div class="w-full overflow-x-auto">
                <form method="post" action="{{ route('open-orders.store') }}">
                    @csrf
                    <div class="m-2 flex justify-between">
                        <h4 class="mb-4 text-xl font-bold text-gray-600 dark:text-gray-300">
                            Open Shop Order Report
                        </h4>
                        <button type="submit"
                                class="flex items-center justify-end p-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <span class="mx-4">Save</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </button>
                    </div>
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
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
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach($openOrders as $openOrder)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][swrkc]"
                                               id="swrkc" value="{{ $openOrder->SWRKC }}" hidden/>
                                        {{ $openOrder->SWRKC }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sddte]"
                                               id="sddte" value="{{ $openOrder->SDDTE }}" hidden/>
                                        {{ $openOrder->SDDTE }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sord]"
                                               id="sord" value="{{ $openOrder->SORD }}" hidden/>
                                        {{ $openOrder->SORD }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sprod]"
                                               id="sprod" value="{{ $openOrder->SPROD }}" hidden/>
                                        {{ $openOrder->SPROD }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sqreq]"
                                               id="sqreq" value="{{ $openOrder->SQREQ }}" hidden/>
                                        {{ $openOrder->SQREQ }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sqfin]"
                                               id="sqfin" value="{{ $openOrder->SQFIN }}" hidden/>
                                        {{ $openOrder->SQFIN }}
                                    </label>
                                </td>
                                <td class="px-2 py-2">
                                    <label class="block text-sm">
                                        <input id="cdte" name="arrayOpenOrders[{{ $openOrder->SORD }}][cdte]"
                                               type="date"
                                               class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                                    </label>
                                </td>
                                <td class="px-2 py-2">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input id="canc" name="arrayOpenOrders[{{ $openOrder->SORD }}][canc]"
                                               type="checkbox" value="1"
                                               class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray"/>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
            <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Show {{ $openOrders->firstItem() }} - {{ $openOrders->lastItem() }}
                </span>
                <!-- Pagination -->
                <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                    {{ $openOrders->withQueryString()->links() }}
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
