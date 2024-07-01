<x-app-layout title="{{ __('Plan de Producción de Prensas') }}">
    <div class="xl:container lg:container md:container sm:container grid px-6 mx-auto">
        <h2 class="mt-6 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Plan de Producción de Prensas')}}
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

        <div class="py-2">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif
            @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('warning') }}</span>
            </div>
            @endif
        </div>

        <div class="flex justify-end px-4 mb-2 gap-4">
            <div>
                <a href="{{ route('production-plan.create') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <span class="mr-4">{{ __('Agregar a Plan') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                </a>
            </div>
            <!-- <div>
                <a href="{{ route('unemployment-record.create') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    <span class="mr-4">{{ __('Registrar Paro') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                    </svg>
                </a>
            </div> -->
        </div>

        <div class="px-4 py-2 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('production-plan.index') }}" method="get">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div class="mb-4 md:mb-0 flex-grow flex-shrink mr-4">
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Número de Parte') }}</span>
                            <input name="part_number" type="text" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="off" />
                        </label>
                    </div>
                    <div class="mb-4 md:mb-0 flex-grow flex-shrink mr-4">
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">{{ __('Fecha') }}</span>
                            <input name="date" type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                    </div>
                    <div>
                        <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            <span class="mr-2">{{ __('Buscar')}}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">{{ __('Estación') }}</th>
                            <th class="px-4 py-3 sticky left-0 z-10 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">{{ __('Número de Parte') }}</th>
                            <th class="px-4 py-3 sticky left-20 z-10 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">{{ __('Fecha') }}</th>
                            <th class="px-4 py-3">{{ __('Turno') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('Cant Planeada') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('Cant Producida') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('Scrap') }}</th>
                            <th class="px-4 py-3">{{ __('Estado') }}</th>
                            <th class="px-4 py-3">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($pressProductionPlans as $pressProductionPlan)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 text-xs">
                                {{ $pressProductionPlan->workcenter_name ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-xs sticky left-0 bg-white dark:bg-gray-800">
                                {{ $pressProductionPlan->part_number ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-xs sticky left-28 bg-white dark:bg-gray-800">
                                {{ $pressProductionPlan->plan_date ? \Carbon\Carbon::parse($pressProductionPlan->date)->format('d-m-Y') : '' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                {{ $pressProductionPlan->shift_abbreviation ?? '' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                    {{ intval($pressProductionPlan->plan_quantity) ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                @if ($pressProductionPlan->plan_quantity < $pressProductionPlan->production_quantity )
                                    <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                                        {{ intval($pressProductionPlan->production_quantity) ?? '' }}
                                    </span>
                                    @elseif ($pressProductionPlan->plan_quantity == $pressProductionPlan->production_quantity )
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-700">
                                        {{ intval($pressProductionPlan->production_quantity) ?? '' }}
                                    </span>
                                    @else
                                    <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                        {{ intval($pressProductionPlan->production_quantity) ?? '' }}
                                    </span>
                                    @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-center">
                                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                    {{ intval($pressProductionPlan->scrap_quantity) ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if ($pressProductionPlan->status_name == 'PENDIENTE')
                                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                    {{ __('Pendiente') }}
                                </span>
                                @elseif ($pressProductionPlan->status_name == 'EN PROCESO')
                                <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                    {{ __('En Proceso') }}
                                </span>
                                @elseif ($pressProductionPlan->status_name == 'PRODUCCIÓN DETENIDA')
                                <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                    {{ __('Producción Detenida') }}
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-xs">
                                    <a href="{{ route('prodcution-record.create', ['production' => $pressProductionPlan->production_plan_id]) }}" class="flex w-full items-center justify-center px-4 py-2 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-full active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" aria-label="{{ __('Edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="ml-1 text-xs">{{ __('Producción') }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            @if ($pressProductionPlans->count())
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{ __('Mostrando') }} {{ $pressProductionPlans->firstItem() }} - {{ $pressProductionPlans->lastItem() }} {{ __('de') }} {{ $pressProductionPlans->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="{{ __('Table navigation') }}">
                        {{ $pressProductionPlans->withQueryString()->links() }}
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
