<x-app-layout title="Planificación y Progreso Diario de la Producción">
    <div class=" gap-y-2 px-4 py-3 mx-auto">
        <h2 class="p-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Planificación y Progreso Diario de la Producción
        </h2>
        <form method="GET" action="{{ route('daily-production.user') }}">
            <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Centro de Trabajo</span>
                    <select id="workCenter" name="workCenter" class="block w-60 text-xs dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray">
                        <option>Selecciona Centro de Trabajo</option>
                        @foreach ($workCenters as $workCenter)
                        <option value="{{ $workCenter->WWRKC }}">
                            {{ $workCenter->WWRKC }} - {{ $workCenter->WDESC }}
                        </option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Fecha de Entrega</span>
                    <input id="dueDate" name="dueDate" type="date" class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <div class="flex justify-center">
                    <button type="submit" class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                        <span class="mr-2">Buscar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
        <div class="w-full overflow-hidden rounded-lg shadow-xs border-2 bg-white dark:bg-gray-800">
            <div class="grid grid-cols-6 px-4 py-3 rounded-t-lg text-xs font-semibold tracking-wide text-gray-600 uppercase border-b dark:border-gray-700 bg-white  dark:text-gray-400 dark:bg-gray-800">
                <div class=" col-span-6 gap-x-4 flex flex-row">
                    <span class="flex items-center">
                        Fecha de Entrega: {{ $date }}
                    </span>
                </div>
            </div>
            <div class="w-full overflow-x-auto text-center">
                <table class="w-full whitespace-no-wrap">
                    @if ($countDiurno > 0 || $countNocturno > 0)
                    <thead>
                        <tr class="text-xs text-center font-semibold tracking-wide text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Centro de Trabajo</th>
                            <th class="px-4 py-3">Turno</th>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">No. Parte</th>
                            <th class="px-4 py-3">Orden de Producción</th>
                            <th class="px-4 py-3">Cantidad Planeada</th>
                            <th class="px-4 py-3">Cantidad Real</th>
                            <th class="px-4 py-3">Cantidad de Desecho</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Inventario</th>
                        </tr>
                    </thead>
                    @endif
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @php
                        $realQuantity = 0;
                        $dayStoragePlan = 0;
                        $dayStorageReal = 0;
                        $dayStorageScrap = 0;
                        $nightStoragePlan = 0;
                        $nightStorageReal = 0;
                        $nightStorageScrap = 0;
                        $inventory = 0;
                        @endphp
                        @foreach ($dailyDiurnos as $key => $dailyDiurno)
                        <tr>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyDiurno->SWRKC }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                D
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $num = $key + 1 }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyDiurno->SPROD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyDiurno->SORD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyDiurno->SQREQ }}
                                @php
                                $dayStoragePlan += $dailyDiurno->SQREQ;
                                @endphp
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @php
                                $realQuantity= $dailyDiurno->SQFIN - $dailyDiurno->SQREMM;
                                $dayStorageReal += $dailyDiurno->SQFIN;
                                @endphp
                                {{ $realQuantity }}.000
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyDiurno->SQREMM }}
                                @php
                                $dayStorageScrap += $dailyDiurno->SQREMM;
                                @endphp
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if ($dailyDiurno->SQREQ <= $dailyDiurno->SQFIN)
                                    <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                                        Finalizado
                                    </span>
                                    @elseif ($dailyDiurno->SQREQ > $dailyDiurno->SQFIN && $dailyDiurno->SID == 'SZ')
                                    <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                        Cancelado
                                    </span>
                                    @else
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                        En Proceso
                                    </span>
                                    @endif
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @php
                                $inventory = $dailyDiurno->IOPB + $dailyDiurno->IRCT - $dailyDiurno->IISS + $dailyDiurno->IADJ;
                                @endphp
                                @if ($inventory < 0) <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                    {{ $inventory }}
                                    </span>
                                    @else
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                        {{ $inventory }}
                                    </span>
                                    @endif
                            </td>
                        </tr>
                        @endforeach
                        @if ($countDiurno > 0)
                        <tr class="bg-gray-100">
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs">Subtotal</td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs">{{ $dayStoragePlan }}.000</td>
                            <td class="px-4 py-3 text-xs">{{ $dayStorageReal - $dayStorageScrap }}.000</td>
                            <td class="px-4 py-3 text-xs">{{ $dayStorageScrap }}.000</td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                        </tr>
                        @endif
                        @foreach ($dailyNocturnos as $key => $dailyNocturno)
                        <tr>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyNocturno->SWRKC }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                N
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $num = $num + 1 }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyNocturno->SPROD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyNocturno->SORD }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyNocturno->SQREQ }}
                                @php
                                $nightStoragePlan += $dailyNocturno->SQREQ;
                                @endphp
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @php
                                $realQuantity= $dailyNocturno->SQFIN - $dailyNocturno->SQREMM;
                                $nightStorageReal += $dailyNocturno->SQFIN;
                                @endphp
                                {{ $realQuantity }}.000
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $dailyNocturno->SQREMM }}
                                @php
                                $nightStorageScrap += $dailyNocturno->SQREMM;
                                @endphp
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if ($dailyNocturno->SQREQ <= $dailyNocturno->SQFIN)
                                    <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                                        Finalizado
                                    </span>
                                    @elseif ($dailyNocturno->SQREQ > $dailyNocturno->SQFIN && $dailyNocturno->SID == 'SZ')
                                    <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                        Cancelado
                                    </span>
                                    @else
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                        En Proceso
                                    </span>
                                    @endif
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @php
                                $inventory = $dailyNocturno->IOPB + $dailyNocturno->IRCT - $dailyNocturno->IISS + $dailyNocturno->IADJ;
                                @endphp
                                @if ($inventory < 0) <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                    {{ $inventory }}
                                    </span>
                                    @else
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                        {{ $inventory }}
                                    </span>
                                    @endif
                            </td>
                        </tr>
                        @endforeach
                        @if ($countNocturno > 0)
                        <tr class="bg-gray-100">
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs">Subtotal</td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs">{{ $nightStoragePlan }}.000</td>
                            <td class="px-4 py-3 text-xs">{{ $nightStorageReal - $nightStorageScrap }}.000</td>
                            <td class="px-4 py-3 text-xs">{{ $nightStorageScrap }}.000</td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                        </tr>
                        @endif
                        @if ($countDiurno > 0 || $countNocturno > 0)
                        <tr class="bg-teal-100">
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs">Total</td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs">{{ $dayStoragePlan + $nightStoragePlan }}.000</td>
                            <td class="px-4 py-3 text-xs">{{ $dayStorageReal + $nightStorageReal - $dayStorageScrap - $nightStorageScrap }}.000</td>
                            <td class="px-4 py-3 text-xs">{{ $dayStorageScrap + $nightStorageScrap }}.000</td>
                            <td class="px-4 py-3 text-xs"></td>
                            <td class="px-4 py-3 text-xs"></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if ($countDiurno > 0 || $countNocturno > 0)
            <div class="grid px-4 py-3 rounded-b-lg text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-white sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{-- Show {{ $dailyProdcution->firstItem() }} - {{ $dailyProdcution->lastItem() }} --}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{-- {{ $dailyProdcution->withQueryString()->links() }} --}}
                        </ul>
                    </nav>
                </span>
            </div>
            @else
            <div class="px-4 py-3 rounded-md text-sm text-center font-semibold text-gray-700 uppercase bg-white sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                Planificación y Progreso Diario No Encontrado
            </div>
            @endif
        </div>
</x-app-layout>
