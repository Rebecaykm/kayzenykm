<x-app-layout title="{{ __('Registro de Scrap') }}">
    <div class="xl:container lg:container md:container sm:container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Registro de Scrap') }}
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

        <div class="flex justify-end mb-4 gap-2">
            <a href="{{ route('scrap-record.report') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                {{ __('Reporte de Scrap') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </a>
            <a href="{{ route('scrap-record.create-scrap') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                {{ __('Registrar Scrap') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap table-fixed">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">{{ __('Estación') }}</th>
                            <th class="px-4 py-3">{{ __('Número de Parte') }}</th>
                            <th class="px-4 py-3">{{ __('Scrap') }}</th>
                            <th class="px-4 py-3">{{ __('Cantidad') }}</th>
                            <th class="px-4 py-3">{{ __('Fecha') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 uppercase dark:bg-gray-800">
                        @foreach ($scrapRecords as $scrapRecord)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-xs">
                                {{ $scrapRecord->partNumber->workcenter->number ?? '' }} - {{ $scrapRecord->partNumber->workcenter->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $scrapRecord->partNumber->number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-xs truncate">
                                {{ $scrapRecord->scrap->code ?? '' }} - {{ $scrapRecord->scrap->name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ intval($scrapRecord->quantity_scrap) ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $scrapRecord->created_at->format('d-m-Y H:i:s') ?? '' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center items-center space-x-4 text-sm">
                                    @if ($scrapRecord->flag)
                                    <span class="flex items-center justify-between text-xs font-medium leading-tight text-gray-500 bg-gray-200 rounded-full dark:text-gray-400 dark:bg-gray-700 px-2 py-1">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </span>
                                    @else
                                    <a href="{{ route('scrap-record.edit', $scrapRecord->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($scrapRecords->count())
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{ __('Mostrando') }} {{ $scrapRecords->firstItem() }} - {{ $scrapRecords->lastItem() }} {{ __('de') }} {{ $scrapRecords->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $scrapRecords->withQueryString()->links() }}
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
</x-app-layout>
