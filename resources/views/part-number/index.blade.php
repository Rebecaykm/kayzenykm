<x-app-layout title="Números de Parte">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Números de Parte')}}
        </h2>

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
        <!--
        <div class="flex justify-end mb-4">
            <a href="{{ route('part-number.create') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                {{ __('Nuevo Número de Parte') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>
        </div>
        -->
        <div class="px-4 py-3 gap-x-2 my-2 bg-white rounded-lg shadow-lg dark:bg-gray-800">
            <label class="block mt-4 text-sm">
                <div class="relative text-gray-500 focus-within:text-purple-600">
                    <form action="{{ route('part-number.index') }}" method="get">
                        <input name="search" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Número de Parte" autocomplete="off"/>
                        <button class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Buscar
                        </button>
                    </form>
                </div>
            </label>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-lg">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <!-- <th class="px-4 py-3">{{ __('Nombre') }}</th> -->
                            <th class="px-4 py-3">{{ __('Numero') }}</th>
                            <th class="px-4 py-3">{{ __('Proyecto') }}</th>
                            <!-- <th class="px-4 py-3">{{ __('Medida') }}</th> -->
                            <th class="px-4 py-3">{{ __('Item') }}</th>
                            <th class="px-4 py-3">{{ __('Clase') }}</th>
                            <th class="px-4 py-3">{{ __('Paquete Estandar') }}</th>
                            <th class="px-4 py-3">{{ __('Cantidad') }}</th>
                            <th class="px-4 py-3">{{ __('Centro de Trabajo') }}</th>
                            <th class="px-4 py-3">{{ __('Código de Planeador') }}</th>
                            <!-- <th class="px-4 py-3">{{ __('Acciones') }}</th> -->
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($partNumbers as $partNumber)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <!-- <td class="px-4 py-3 text-sm">
                                {{ $partNumber->name ?? '' }}
                            </td> -->
                            <td class="px-4 py-3 text-sm">
                                {{ $partNumber->number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @foreach ($partNumber->projects as $project)
                                {{ $project->model  ?? '' }}
                                @endforeach
                            </td>
                            <!-- <td class="px-4 py-3 text-sm">
                                {{ $partNumber->measurement->symbol ?? '' }}
                            </td> -->
                            <td class="px-4 py-3 text-sm">
                                {{ $partNumber->type->abbreviation ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $partNumber->itemClass->abbreviation ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $partNumber->standardPackage->name ?? ''}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $partNumber->quantity ?? ''}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $partNumber->workcenter->name ?? ''}}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                {{ $partNumber->planner->name ?? ''}}
                            </td>
                            <!-- <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('part-number.edit', $partNumber->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('part-number.destroy', $partNumber->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($partNumbers->count())
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{ __('Mostrando') }} {{ $partNumbers->firstItem() }} - {{ $partNumbers->lastItem() }} {{ __('de') }} {{ $partNumbers->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        {{ $partNumbers->withQueryString()->links() }}
                    </nav>
                </span>
            </div>
            @else
            <div class="px-4 py-3 rounded-md text-sm text-center font-semibold text-gray-700 uppercase bg-white sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                {{ __('No Se Han Encontrado Unidades de Medida') }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
