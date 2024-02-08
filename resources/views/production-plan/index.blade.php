<x-app-layout title="{{ __('Plan de Producción') }}">
    <div class="xl:container lg:container md:container sm:container grid px-6 mx-auto">
        <h2 class="mt-6 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Plan de Producción')}}
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
            @elseif(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif
        </div>

        <div class="flex justify-end px-4 gap-4">
            <div>
                <a href="{{ route('production-plan.create') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <span class="mr-4">{{ __('Agregar a Plan') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </a>
            </div>
            <div>
                <a href="{{ route('unemployment-record.create') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    <span class="mr-4">{{ __('Registrar Paro') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="px-4 py-3 gap-x-2 my-2 bg-white rounded-lg shadow-lg dark:bg-gray-800">
            <label class="block text-sm">
                <div class="relative text-gray-500 focus-within:text-purple-600">
                    <form action="{{ route('production-plan.index') }}" method="get">
                        <input name="search" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="{{ __('Número de Parte') }}" autocomplete="off" />
                        <button class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            {{ __('Buscar') }}
                        </button>
                    </form>
                </div>
            </label>
        </div>

        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <!-- <th class="px-4 py-3">{{ __('ID') }}</th> -->
                            <th class="px-4 py-3">{{ __('Estación') }}</th>
                            <th class="px-4 py-3">{{ __('Número de Parte') }}</th>
                            <!-- <th class="px-4 py-3">{{ __('SNP') }}</th> -->
                            <th class="px-4 py-3">{{ __('Fecha') }}</th>
                            <th class="px-4 py-3">{{ __('Turno') }}</th>
                            <!-- <th class="px-4 py-3">{{ __('Tipo de Item') }}</th> -->
                            <th class="px-4 py-3 text-center">{{ __('Cant Planeada') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('Cant Producida') }}</th>
                            <th class="px-4 py-3 text-center">{{ __('Scrap') }}</th>
                            <th class="px-4 py-3">{{ __('Estado') }}</th>
                            <th class="px-4 py-3">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($productionPlans as $productionPlan)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <!-- <td class="px-4 py-3 text-xs">
                                {{ $productionPlan->production_plan_id ?? '' }}
                            </td> -->

                            <td class="px-4 py-3 text-xs">
                                {{ $productionPlan->partNumber->workcenter->number ?? '' }} - {{ $productionPlan->partNumber->workcenter->name ?? '' }}
                            </td>

                            <td class="px-4 py-3 text-xs">
                                {{ $productionPlan->partNumber->number ?? '' }}
                            </td>

                            <!-- <td class="px-4 py-3 text-xs">
                                {{ $productionPlan->partNumber->standardPackage->name ?? '' }} - {{ $productionPlan->partNumber->quantity ?? '' }}
                            </td> -->

                            <td class="px-4 py-3 text-xs">
                                {{ $productionPlan->date ? \Carbon\Carbon::parse($productionPlan->date)->format('d-m-Y') : '' }}
                            </td>

                            <td class="px-4 py-3 text-xs text-center">
                                {{ $productionPlan->shift->abbreviation ?? '' }}
                            </td>

                            <!-- <td class="px-4 py-3 text-xs">
                                {{ $productionPlan->partNumber->itemClass->abbreviation ?? '' }}
                            </td> -->

                            <td class="px-4 py-3 text-xs text-center">
                                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                    {{ $productionPlan->plan_quantity ?? '' }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-xs text-center">
                                @if ( $productionPlan->plan_quantity < $productionPlan->production_quantity )
                                    <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                                        {{ $productionPlan->production_quantity ?? '' }}
                                    </span>
                                    @elseif ($productionPlan->plan_quantity == $productionPlan->production_quantity )
                                    <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-700">
                                        {{ $productionPlan->production_quantity ?? '' }}
                                    </span>
                                    @else
                                    <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                        {{ $productionPlan->production_quantity ?? '' }}
                                    </span>
                                    @endif
                            </td>

                            <td class="px-4 py-3 text-xs text-center">
                                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                    {{ $productionPlan->scrap_quantity }}
                                </span>
                            </td>

                            @if ($productionPlan->status->name == 'PENDIENTE')
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                    {{ __('Pendiente') }}
                                </span>
                            </td>
                            @elseif ($productionPlan->status->name == 'EN PROCESO')
                            <td class="px-4 py-3 text-xs">
                                <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                                    {{ __('En Proceso') }}
                                </span>
                            </td>
                            @else
                            <td class="px-4 py-3 text-xs">

                            </td>
                            @endif

                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-xs">
                                    <a href="{{ route('prodcution-record.create', ['production' => $productionPlan->production_plan_id]) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="{{ __('Edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                        <span class="ml-1 text-xs">{{ __('Registrar') }}</span>
                                    </a>
                                    <a href="{{ route('scrap-record.create', ['item' => $productionPlan->part_number_id, 'production' => $productionPlan->production_plan_id]) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="{{ __('Edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span class="ml-1 text-xs">{{ __('Scrap') }}</span>
                                    </a>
                                    <a href="{{ route('production-plan.finish', ['production' => $productionPlan->production_plan_id]) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-purple-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="{{ __('Edit') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                        </svg>
                                        <span class="ml-1 text-xs">{{ __('Finalizar') }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($productionPlans->count())
            <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{ __('Mostrando') }} {{ $productionPlans->firstItem() }} - {{ $productionPlans->lastItem() }} {{ __('de') }} {{ $productionPlans->total() }}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="{{ __('Table navigation') }}">
                        {{ $productionPlans->withQueryString()->links() }}
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
