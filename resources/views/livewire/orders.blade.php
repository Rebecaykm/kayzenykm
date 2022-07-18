<div>
    <div class="container grid px-6 mx-auto">
        <h2 class="my-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Open shop order report
        </h2>
        <div class="w-full overflow-hidden rounded-lg shadow-xs my-2">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-3 py-2">WORK CENTER</th>
                        <th class="px-3 py-2">DUE DATE</th>
                        <th class="px-3 py-2">SHOP ORDER NUMBER</th>
                        <th class="px-3 py-2">ITEM NUMBER</th>
                        <th class="px-3 py-2">QUANTITY REQUIRED</th>
                        <th class="px-3 py-2">QUANTITY FINISHED</th>
                        <th class="px-3 py-2">DATA CHANGE</th>
                        <th class="px-3 py-2">CANCEL</th>
                        <th class="px-3 py-2">ACTION</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($orders as $order)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-3 py-2 text-xs">
                                {{ $order->SWRKC }}
                            </td>
                            <td class="px-3 py-2 text-xs">
                                {{ $order->SDDTE }}
                            </td>
                            <td class="px-3 py-2 text-xs">
                                {{ $order->SORD }}
                            </td>
                            <td class="px-3 py-2 text-xs">
                                {{ $order->SPROD }}
                            </td>
                            <td class="px-3 py-2 text-xs">
                                {{ $order->SQREQ }}
                            </td>
                            <td class="px-3 py-2 text-xs">
                                {{ $order->SQFIN }}
                            </td>
                            <td class="px-3 py-2">
                                <label class="block text-sm">
                                    <input type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                                </label>
                            </td>
                            <td class="px-3 py-2">
                                <label class="flex items-center justify-center dark:text-gray-400">
                                    <input type="checkbox" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"/>
                                </label>
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center justify-center space-x-4 text-sm">
                                    <button class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-3 py-2 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                    <span class="flex items-center col-span-3">
                        Y-TEC KEYLEX MÉXICO
                    </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    {{ $orders->links() }}
                </span>
            </div>
        </div>
    </div>
</div>
