<x-app-layout title="Open Orders">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Reporte de Ordenes de Producción
        </h2>
        <!-- With actions -->
        <!--
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4>
        -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Work Center</th>
                        <th class="px-4 py-3">Due Date</th>
                        <th class="px-4 py-3">Shop Order Number</th>
                        <th class="px-4 py-3">Item Number</th>
                        <th class="px-4 py-3">Quantity Required</th>
                        <th class="px-4 py-3">Quantity Finished</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($openOrders as $openOrder)
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
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('open-orders.show', ['sord' => $openOrder->SORD]) }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                </span>
                <!-- Pagination -->
                <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                    {{ $openOrders->links() }}
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
