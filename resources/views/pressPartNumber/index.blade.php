<x-app-layout title="{{ __('Número de Parte Prensas') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Número de Parte Prensas') }}
        </h2>

        <div class="flex justify-end mb-4">
            <a href="{{ route('press-part-number.file') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                {{ __('Importar Números de Parte') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </a>
        </div>

        <div class="px-4 py-3 gap-x-2 my-2 bg-white rounded-lg shadow-lg dark:bg-gray-800">
            <label class="block text-sm">
                <div class="relative text-gray-500 focus-within:text-purple-600">
                    <form action="{{ route('press-part-number.index') }}" method="get">
                        <input name="search" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Número de Parte" autocomplete="off" />
                        <button class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Buscar
                        </button>
                    </form>
                </div>
            </label>
        </div>

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
