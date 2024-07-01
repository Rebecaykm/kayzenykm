<x-app-layout title="{{ __('Número de Parte Prensas') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Número de Parte Prensas') }}
        </h2>

        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">{{ __('Número de Parte') }}</th>
                            <th class="px-4 py-3">{{ __('Piezas por Golpe') }}</th>
                            <th class="px-4 py-3">{{ __('Números de Parte Relacionados') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($pressPartNumbers as $pressPartNumber)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-xs">{{ $pressPartNumber->part_number ?? '' }}</td>
                            <td class="px-4 py-3 text-xs">{{ $pressPartNumber->pieces_per_hit ?? '' }}</td>
                            <td class="px-4 py-3 text-xs whitespace-pre-line uppercase">
                                @foreach ($pressPartNumber->partNumbers as $index => $partNumber)
                                    {{ $partNumber->workcenter->name ?? '' }} - {{ $partNumber->number }}
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($pressPartNumbers->count())
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{ __('Mostrando') }} {{ $pressPartNumbers->firstItem() }} - {{ $pressPartNumbers->lastItem() }} {{ __('de') }} {{ $pressPartNumbers->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $pressPartNumbers->withQueryString()->links() }}
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