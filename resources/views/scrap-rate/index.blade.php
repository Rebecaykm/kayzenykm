<x-app-layout title="Tasa de Scrap">
    <div class="container grid px-6 mx-auto">
        <div class="flex flex-col my-6 space-y-4 md:space-y-0 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Tasa de Scrap
            </h2>

            <!-- Barra de búsqueda (ocupa todo el ancho disponible) -->
            <form action="{{ route('scrap-rate.index') }}" method="GET" class="w-full md:w-1/2">
                <div class="relative">
                    <input type="text" name="search" placeholder="Buscar por número de parte..."
                        class="block w-full pr-10 pl-4 py-2 text-sm border-gray-300 rounded-lg shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-purple-500"
                        value="{{ request('search') }}">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <button type="submit" class="p-1 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 text-sm font-semibold text-green-700 bg-green-100 border border-green-400 rounded-lg dark:bg-green-700 dark:text-green-100">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 px-4 py-3 text-sm font-semibold text-red-700 bg-red-100 border border-red-400 rounded-lg dark:bg-red-700 dark:text-red-100">
            {{ session('error') }}
        </div>
        @endif

        <!-- Tabla de Scrap Rate -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Número de Parte</th>
                            <th class="px-4 py-3">Fecha de Inicio</th>
                            <th class="px-4 py-3">Tasa de Scrap</th>
                            <th class="px-4 py-3">Fecha de Creación</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($scrapRateProducedParts as $item)
                        <tr class="text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 dark:text-gray-200">
                                    {{ $item->SCPROD ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ isset($item->SCSTDT) ? \Carbon\Carbon::createFromFormat('Ymd', $item->SCSTDT)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if(isset($item->SCRATE))
                                <span class="px-3 py-1 text-xs font-semibold leading-tight rounded-full
                                    {{ $item->SCRATE > 0.25 ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' }}">
                                    {{ number_format($item->SCRATE * 100, 2) }}%
                                </span>
                                @else
                                -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if(isset($item->SCCRDT) && isset($item->SCCRTM))
                                {{ \Carbon\Carbon::createFromFormat('Ymd His', $item->SCCRDT . ' ' . $item->SCCRTM)->format('d/m/Y H:i') }}
                                @else
                                -
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('scrap-rate.edit', [
                                        'partNumber' => $item->SCPROD,
                                        'startDate' => $item->SCSTDT,
                                        'scrapRate' => $item->SCRATE,
                                        'createdDate' => $item->SCCRDT,
                                        'createdTime' => $item->SCCRTM,
                                        'createdUser' => $item->SCCRUS,
                                        'createdWs' => $item->SCCRWS
                                    ]) }}"
                                    class="inline-flex items-center p-2 text-sm font-medium text-center text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-500 dark:hover:bg-purple-600 dark:focus:ring-purple-800"
                                    title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 mb-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-600 dark:text-gray-300">No hay registros disponibles</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($scrapRateProducedParts->hasPages())
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    <!--  -->
                </span>
                <span class="col-span-2"></span>
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    {{ $scrapRateProducedParts->appends(['search' => request('search')])->links() }}
                </span>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
