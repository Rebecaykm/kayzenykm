<x-app-layout title="Informe de Órdenes Abiertas">
    <div class="grid px-3 py-4 mx-auto gap-y-2">
        <h2 class="p-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Informe de Órdenes Abiertas
        </h2>
        <div class="w-full overflow-hidden rounded-lg p-2">
            <form method="GET" action="{{ route('open-orders.index') }}">
                <div class="flex flex-row justify-end gap-2 py-2">
                    <div class="flex justify-center">
                        <div class="relative w-48 max-w-xl focus-within:text-blue-500">
                            <div class="absolute inset-y-0 flex items-center pl-2">
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input id="swrkc" name="swrkc" class="w-full pl-8 pr-2 text-xs text-gray-700 placeholder-gray-300 bg-white border-2 rounded-md dark:placeholder-gray-300 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-300 dark:bg-gray-700 dark:text-gray-200 focus:placeholder-gray-200 focus:bg-white focus:border-blue-300 focus:outline-none focus:shadow-outline-blue form-input" type="text" placeholder="Centro de Trabajo" aria-label="Search" autocomplete="off" />
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="relative w-40 max-w-xl focus-within:text-blue-500">
                            <div class="absolute inset-y-0 flex items-center pl-2">
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input id="sord" name="sord" class="w-full pl-8 pr-2 text-xs text-gray-700 placeholder-gray-300 bg-white border-2 rounded-md dark:placeholder-gray-300 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-300 dark:bg-gray-700 dark:text-gray-400 focus:placeholder-gray-200 focus:bg-white focus:border-blue-300 focus:outline-none focus:shadow-outline-blue form-input" type="text" placeholder="Orden de Producción" aria-label="Search" autocomplete="off" />
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="relative w-40 max-w-xl focus-within:text-blue-500">
                            <div class="absolute inset-y-0 flex items-center pl-2">
                                <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input id="sprod" name="sprod" class="w-full pl-8 pr-2 text-xs text-gray-700 placeholder-gray-300 bg-white border-2 rounded-md dark:placeholder-gray-300 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-300 dark:bg-gray-700 dark:text-gray-400 focus:placeholder-gray-200 focus:bg-white focus:border-blue-300 focus:outline-none focus:shadow-outline-blue form-input" type="text" placeholder="Número de Parte" aria-label="Search" autocomplete="off" />
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="relative w-40 max-w-xl focus-within:text-blue-500">
                            <input id="due_date" name="due_date" class="w-full text-xs text-gray-700 placeholder-gray-300 bg-whit-100 border-2 rounded-md dark:placeholder-gray-300 dark:focus:shadow-outline-gray dark:focus:placeholder-gray-300 dark:bg-gray-700 dark:text-gray-400 focus:placeholder-gray-200 focus:bg-white focus:border-blue-300 focus:outline-none focus:shadow-outline-blue form-input" type="date" />
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="relative max-w-xl focus-within:text-blue-500">
                            <button type="submit" class="flex items-center justify-end text-xs font-medium leading-5 px-3 py-2 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                                <span class="mr-2">Buscar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2h-1.528A6 6 0 004 9.528V4z" />
                                    <path fill-rule="evenodd" d="M8 10a4 4 0 00-3.446 6.032l-1.261 1.26a1 1 0 101.414 1.415l1.261-1.261A4 4 0 108 10zm-2 4a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if ($errors->any())
        <div class="mb-4">
            <div class="font-semibold text-red-600">¡Oh no! Algo salió mal.</div>

            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="mb-4 text-lg text-center font-semibold text-green-500">
            {{ session('success') }}
        </div>
        @endif
        <div class="w-full overflow-hidden rounded-lg shadow-xs dark:bg-gray-800">
            <form method="POST" action="{{ route('open-orders.store') }}">
                @csrf
                <div class="grid px-1 py-2 rounded-t-lg text-xs font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-white grid-cols-6 dark:text-gray-400 dark:bg-gray-800">
                    <div class="col-span-3 flex flex-row">

                    </div>
                    <div class="col-span-3 gap-x-2  flex justify-end">
                        <!-- <span class="flex items-center">
                            {{ $openOrders->withQueryString()->links() }}
                        </span> -->
                        <button type="submit"
                            class="flex items-center justify-end text-xs font-medium leading-5 px-3 py-2 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <span class="mr-2">Actualizar</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="w-full overflow-x-auto">
                    @if ($openOrders->count())
                        <table class="relative w-full whitespace-no-wrap rounded-lg">
                            <thead>
                                <tr class="sticky top-0 text-xs font-semibold tracking-wide text-center text-gray-600 uppercase border-b-2 dark:border-gray-800 bg-gray-200 dark:text-gray-400 dark:bg-gray-800">
                                    <th class="px-1 py-2">Centro de Trbajao</th>
                                    <th class="px-1 py-2">Fecha de Entrega</th>
                                    <th class="px-1 py-2">Orden de Producción</th>
                                    <th class="px-1 py-2">No. Parte</th>
                                    <th class="px-1 py-2">Cantidad Requerida</th>
                                    <th class="px-1 py-2">Cantidad Finalizada</th>
                                    <th class="px-1 py-2">Cambio de Fecha</th>
                                    <th class="px-1 py-2">Cancelar</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-800 dark:bg-gray-800">
                                @foreach ($openOrders as $openOrder)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-1 py-2 text-xs">
                                            <label class="flex items-center justify-center dark:text-gray-400">
                                                <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][swrkc]" id="swrkc" value="{{ $openOrder->SWRKC }}" hidden />
                                                {{ $openOrder->SWRKC }}
                                            </label>
                                        </td>
                                        <td class="px-1 py-2 text-xs">
                                            <label class="flex items-center justify-center dark:text-gray-400">
                                                <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sddte]" id="sddte" value="{{ $openOrder->SDDTE }}" hidden />
                                                {{ $openOrder->SDDTE }}
                                            </label>
                                        </td>
                                        <td class="px-1 py-2 text-xs">
                                            <label class="flex items-center justify-center dark:text-gray-400">
                                                <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sord]" id="sord" value="{{ $openOrder->SORD }}" hidden />
                                                {{ $openOrder->SORD }}
                                            </label>
                                        </td>
                                        <td class="px-1 py-2 text-xs">
                                            <label class="flex items-center justify-center dark:text-gray-400">
                                                <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sprod]" id="sprod" value="{{ $openOrder->SPROD }}" hidden />
                                                {{ $openOrder->SPROD }}
                                            </label>
                                        </td>
                                        <td class="px-1 py-2 text-xs">
                                            <label class="flex items-center justify-center dark:text-gray-400">
                                                <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sqreq]" id="sqreq" value="{{ $openOrder->SQREQ }}" hidden />
                                                {{ $openOrder->SQREQ }}
                                            </label>
                                        </td>
                                        <td class="px-1 py-2 text-xs">
                                            <label class="flex items-center justify-center dark:text-gray-400">
                                                <input type="text" name="arrayOpenOrders[{{ $openOrder->SORD }}][sqfin]" id="sqfin" value="{{ $openOrder->SQFIN }}" hidden />
                                                {{ $openOrder->SQFIN }}
                                            </label>
                                        </td>
                                        <td class="px-1 py-2">
                                            <label class="block text-xs">
                                                <input id="date{{ $openOrder->SORD }}" name="arrayOpenOrders[{{ $openOrder->SORD }}][cdte]" type="date" onclick="disableInputCancel({{ $openOrder->SORD }})" class="block w-36 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>
                                        </td>
                                        <td class="px-1 py-2">
                                            <label class="flex items-center justify-center dark:text-gray-400">
                                                <input id="cancel{{ $openOrder->SORD }}" name="arrayOpenOrders[{{ $openOrder->SORD }}][canc]" type="checkbox" value="1" onclick="disableInputDate({{ $openOrder->SORD }})" class="text-blue-600 form-checkbox focus:border-blue-800 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray" />
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </form>
            @if ($openOrders->count())
                <div class="grid px-4 py-3 text-xs rounded-md font-semibold tracking-wide text-gray-700 uppercase border-t dark:border-gray-700 bg-white grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                    <span class="flex items-center col-span-3">
                        Mostrando {{ $openOrders->firstItem() }} - {{ $openOrders->lastItem() }} de {{ $totalOrder }}
                    </span>
                    <!-- Pagination -->
                    <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                        {{ $openOrders->withQueryString()->links() }}
                    </span>
                </div>
            @else
                <div class="px-4 py-3 rounded-md text-sm text-center font-semibold text-gray-700 uppercase bg-white sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                    Órdenes abiertas no encontradas
                </div>
            @endif
        </div>
    </div>
    <script>
        function disableInputCancel($value) {
            let value = $value;
            let inputCancel = "cancel" + value;
            document.getElementById(inputCancel).disabled = true;
        }

        function disableInputDate($value) {
            let value = $value;
            let inputDate = "date" + value
            document.getElementById(inputDate).disabled = true;
        }
    </script>
</x-app-layout>

