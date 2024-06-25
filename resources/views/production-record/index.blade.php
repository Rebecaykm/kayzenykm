<x-app-layout title="{{ __('Registro de Producción') }}">
    <div class="xl:container lg:container md:container sm:container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Registro de Producción') }}
        </h2>

        @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">{{ __('¡Oh no! Algo salió mal.') }}</div>

            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('prodcution-record.report') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                {{ __('Reporte') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </a>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">{{ __('Línea') }}</th>
                            <th class="px-4 py-3">{{ __('Estación') }}</th>
                            <th class="px-4 py-3 sticky left-0 z-10 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">{{ __('Número de Parte') }}</th>
                            <th class="px-4 py-3">{{ __('Cantidad') }}</th>
                            <th class="px-4 py-3">{{ __('Secuencia') }}</th>
                            <th class="px-4 py-3">{{ __('Estado') }}</th>
                            <!-- <th class="px-4 py-3">{{ __('Fecha de Registro') }}</th> -->
                            <th class="px-4 py-3">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($productionRecords as $prodcutionRecord)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-xs sticky left-0 bg-white dark:bg-gray-800">
                                {{ $prodcutionRecord->productionPlan->partNumber->workcenter->line->name }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $prodcutionRecord->productionPlan->partNumber->workcenter->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-xs sticky left-0 bg-white dark:bg-gray-800">
                                {{ $prodcutionRecord->productionPlan->partNumber->number }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ intval($prodcutionRecord->quantity_produced) }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $prodcutionRecord->sequence }}
                            </td>
                            @if ($prodcutionRecord->status->name == 'DENTRO DE PLANEACIÓN')
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                    {{ __('Dentro de Planeación') }}
                                </span>
                            </td>
                            @elseif ($prodcutionRecord->status->name == 'EXCEDENTE DE PLANEACIÓN')
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-yellow-100 dark:bg-yellow-700">
                                    {{ __('Excedente de Planeación') }}
                                </span>
                            </td>
                            @elseif ($prodcutionRecord->status->name == 'CANCELADO')
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                    {{ __('Etiqueta Cancelada') }}
                                </span>
                            </td>
                            @else
                            <td class="px-4 py-3 text-xs">

                            </td>
                            @endif
                            <!-- <td class="px-4 py-3 text-xs">
                                {{ $prodcutionRecord->created_at->format('d-m-Y H:i:s') }}
                            </td> -->
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    @if ($prodcutionRecord->status->name != 'CANCELADO')
                                    <button onclick="imprimir('{{ $prodcutionRecord->prodcution_record_id }}')" class="flex items-center justify-between px-2 py-1 text-xs font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="{{ __('Reimprimir') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        {{ __('Imprimir') }}
                                    </button>
                                    @else
                                    <span class="flex items-center justify-between text-xs font-medium leading-tight text-gray-500 bg-gray-200 rounded-full dark:text-gray-400 dark:bg-gray-700 px-2 py-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        {{ __('Imprimir') }}
                                    </span>
                                    @endif
                                    @if ($prodcutionRecord->status->name != 'CANCELADO')
                                    <a href="{{ route('prodcution-record.cancel', $prodcutionRecord->prodcution_record_id) }}" class="flex items-center justify-between px-2 py-1 text-xs font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="{{ __('Cancelar') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('Cancelar') }}
                                    </a>
                                    @else
                                    <span class="flex items-center justify-between text-xs font-medium leading-tight text-gray-500 bg-gray-200 rounded-full dark:text-gray-400 dark:bg-gray-700 px-2 py-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ __('Cancelar') }}
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($productionRecords->count())
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{ __('Mostrando') }} {{ $productionRecords->firstItem() }} - {{ $productionRecords->lastItem() }} {{ __('de') }} {{ $productionRecords->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $productionRecords->withQueryString()->links() }}
                    </nav>
                </span>
            </div>
            @else
            <div class="px-4 py-3 rounded-md text-sm text-center font-semibold text-gray-700 uppercase bg-white sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                {{ __('No Se Han Encontrado Datos') }}
            </div>
            @endif
        </div>
    </div>
    <script>
        function imprimir(prodcutionRecordId) {

            var ventanaImpresion = window.open('{{ url("prodcution-record") }}/' + prodcutionRecordId + '/reprint');

            ventanaImpresion.onload = function() {
                ventanaImpresion.print();
                window.location.reload();
            };
        }
    </script>
</x-app-layout>
