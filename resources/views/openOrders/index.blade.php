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

        @if ($errors->any())
            <div class="mb-4">
                <div class="font-medium text-red-600">¡Oh no! Algo salió mal.</div>

                <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif


        <form method="post" action="{{ route('open-orders.store') }}">
            @csrf
            <div class="m-2 flex flex-row-reverse">
                <button type="submit" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    <span>Guardar Cambios</span>
                    <svg class="w-4 h-4 ml-2 -mr-1" fill="currentColor" aria-hidden="true" viewBox="0 0 20 20">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-600 uppercase border-b dark:border-gray-800 bg-gray-150 dark:text-gray-500 dark:bg-gray-900">
                            <th class="px-2 py-2">Record ID</th>
                            <th class="px-2 py-2">Work Center</th>
                            <th class="px-2 py-2">Due Date</th>
                            <th class="px-2 py-2">Shop Order Number</th>
                            <th class="px-2 py-2">Item Number</th>
                            <th class="px-2 py-2">Quantity Required</th>
                            <th class="px-2 py-2">Quantity Finished</th>
                            <th class="px-3 py-2">DATA CHANGE</th>
                            <th class="px-3 py-2">CANCEL</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-800 dark:bg-gray-900"
                        @foreach($openOrders as $openOrder)
                            <tr class="text-gray-800 dark:text-gray-500">
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sid]" id="sid" value="{{ $openOrder->SID }}" hidden/>
                                        {{ $openOrder->SID }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][swrkc]" id="swrkc" value="{{ $openOrder->SWRKC }}" hidden/>
                                        {{ $openOrder->SWRKC }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sddte]" id="sddte" value="{{ $openOrder->SDDTE }}" hidden/>
                                        {{ $openOrder->SDDTE }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sord]" id="sord" value="{{ $openOrder->SORD }}" hidden/>
                                        {{ $openOrder->SORD }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sprod]" id="sprod" value="{{ $openOrder->SPROD }}" hidden/>
                                        {{ $openOrder->SPROD }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sqreq]" id="sqreq" value="{{ $openOrder->SQREQ }}" hidden/>
                                        {{ $openOrder->SQREQ }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sqfin]" id="sqfin" value="{{ $openOrder->SQFIN }}" hidden/>
                                        {{ $openOrder->SQFIN }}
                                    </label>
                                </td>
                                <td class="px-2 py-2">
                                    <label class="block text-sm">
                                        <input id="cdte" name="arrayOpenOrders[{{ $openOrder->SORD }}][cdte]" type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"/>
                                    </label>
                                </td>
                                <td class="px-2 py-2">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input id="canc" name="arrayOpenOrders[{{ $openOrder->SORD }}][canc]" type="checkbox" value="1"class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray"/>
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
                </span>
                    <!-- Pagination -->
                    <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                    {{ $openOrders->links() }}
                </span>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
