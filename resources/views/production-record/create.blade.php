<x-app-layout title="Registro de Producción">
    <div class="container grid px-6 mx-auto">
        <h2 class="mt-6 mb-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Registro de Producción
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

        @if(session('success'))
        <div class="py-2">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
        @elseif(session('error'))
        <div class="py-2">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <div class="flex flex-col justify-end flex-wrap mb-4 space-y-4 md:flex- md:items-end md:space-x-4">
            <a href="{{ route('scrap-record.create', ['item' => $productionPlan->partNumber->id, 'production' => $productionPlan->id]) }}" class="flex px-10 py-2 items-center justify-center text-sm font-mediumleading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="ml-1 text-xs">{{ __('Scrap') }}</span>
            </a>
        </div>

        <div class="grid gap-2 mb-2 grid-cols-1 md:grid-cols-3 lg:grid-cols-3">
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                    {{__('Cantidad Plan')}}
                </h4>
                <span class="block w-full mt-1 px-10 py-2 text-lg text-center font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-700">
                    {{ intval($productionPlan->plan_quantity) }}
                </span>
            </div>
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                    {{__('Cantidad Producida')}}
                </h4>
                <span class="block w-full mt-1 px-10 py-2 text-lg text-center font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-700">
                    {{ intval($productionPlan->production_quantity) }}
                </span>
            </div>
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <h4 class="mb-4 font-semibold text-gray-600 dark:text-gray-300">
                    {{__('Diferencia')}}
                </h4>
                @if (intval($productionPlan->production_quantity) == intval($productionPlan->plan_quantity))
                <span class="block w-full mt-1 px-10 py-2 text-lg text-center font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:text-white dark:bg-green-600">
                    {{ intval($productionPlan->production_quantity) - intval($productionPlan->plan_quantity) }}
                </span>
                @elseif (intval($productionPlan->production_quantity) > intval($productionPlan->plan_quantity))
                <span class="block w-full mt-1 px-10 py-2 text-lg text-center font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                    {{ intval($productionPlan->production_quantity) - intval($productionPlan->plan_quantity) }}
                </span>
                @else
                <span class="block w-full mt-1 px-10 py-2 text-lg text-center font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-white dark:bg-red-600">
                    {{ intval($productionPlan->production_quantity) - intval($productionPlan->plan_quantity) }}
                </span>
                @endif
            </div>
        </div>

        <div class="grid gap-2 mb-2 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Departamento')}}
                    </p>
                    <p class="text-lg font-semibold uppercase text-gray-700 dark:text-gray-200">
                        {{ $productionPlan->partNumber->workcenter->line->departament->name }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Estación')}}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $productionPlan->partNumber->workcenter->number }} - {{ $productionPlan->partNumber->workcenter->name }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Proyecto')}}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $productionPlan->partNumber->projects->implode('model', ', ') }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Número de Parte')}}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $productionPlan->partNumber->number }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Tipo SNP')}}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $productionPlan->partNumber->standardPackage->name }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Cantidad SNP')}}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ intval($productionPlan->partNumber->quantity) }}
                    </p>
                </div>
            </div>
        </div>

        <input type="hidden" name="production_plan_id" value="{{ $productionPlan->id }}">
        <input type="hidden" name="part_number_id" value="{{ $productionPlan->partNumber->id }}">

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="mt-2">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                    @if ($productionPlan->status->name == 'PENDIENTE' || $productionPlan->status->name == 'PRODUCCIÓN DETENIDA')
                    <div class="block md:col-span-3 lg:grid-cols-3">
                        <form action="{{ route('prodcution-record.start-production') }}" method="POST">
                            @csrf
                            <input type="hidden" name="productionPlananId" value="{{ $productionPlan->id }}">
                            <button type="submit" class="w-full md:w-auto lg:w-auto px-10 py-2 text-lg font-semibold leading-tight text-purple-700 transition-colors duration-150 bg-purple-100 border border-transparent rounded-lg active:bg-purple-100 hover:bg-purple-100 focus:outline-none focus:shadow-outline-purple dark:bg-purple-700 dark:text-white">
                                <div class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="ml-2">Iniciar Producción</span>
                                </div>
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="block">
                        <button id="etiquetaButton" onclick="imprimirPDF()" class="w-full md:w-auto lg:w-auto px-10 py-2 font-semibold leading-tight text-purple-700 transition-colors duration-150 bg-purple-100 border border-transparent rounded-lg active:bg-purple-100 hover:bg-purple-100 focus:outline-none focus:shadow-outline-purple dark:text-purple-100 dark:bg-purple-700">
                            <div class="flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <span class="ml-2">Etiqueta</span>
                            </div>
                        </button>
                    </div>
                    <div class="block">
                        <a href="{{ route('prodcution-record.bais', ['production' => $productionPlan->id]) }}">
                            <button class="w-full md:w-auto lg:w-auto px-10 py-2 font-semibold leading-tight text-purple-700 transition-colors duration-150 bg-purple-100 border border-transparent rounded-lg active:bg-purple-100 hover:bg-purple-100 focus:outline-none focus:shadow-outline-purple dark:text-purple-100 dark:bg-purple-700">
                                <div class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                                    </svg>
                                    <span class="ml-2">Ingresar Parcialidad</span>
                                </div>
                            </button>
                        </a>
                    </div>
                    <div class="block">
                        <a href="{{ route('production-plan.finish', ['production' => $productionPlan->id]) }}">
                            <button id="finalizarButton" onclick="finalizarProduccion()" class="w-full md:w-auto lg:w-auto px-10 py-2 font-semibold leading-tight text-purple-700 transition-colors duration-150 bg-purple-100 border border-transparent rounded-lg active:bg-purple-100 hover:bg-purple-100 focus:outline-none focus:shadow-outline-purple dark:text-purple-100 dark:bg-purple-700">
                                <div class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="ml-2">Finalizar</span>
                                </div>
                            </button>
                        </a>
                    </div>

                    <div class="block">
                        <a href="{{ route('production-plan.index') }}">
                            <button class="w-full md:w-auto lg:w-auto px-10 py-2 font-semibold leading-tight text-gray-700 transition-colors duration-150 bg-gray-200 border border-transparent rounded-lg active:bg-gray-200 hover:bg-gray-200 focus:outline-none focus:shadow-outline-gray dark:text-white dark:bg-gray-500">
                                <div class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                                    </svg>
                                    <span class="ml-2">{{ __('Regresar') }}</span>
                                </div>
                            </button>
                        </a>
                    </div>

                    <div class="block md:col-span-1 lg:col-span-2">
                        <form action="{{ route('prodcution-record.stop-production') }}" method="POST">
                            @csrf
                            <input type="hidden" name="productionPlananId" value="{{ $productionPlan->id }}">
                            <button type="submit" class="w-full md:w-auto lg:w-auto px-10 py-2 font-semibold leading-tight text-yellow-700 transition-colors duration-150 bg-yellow-100 border border-transparent rounded-lg active:bg-yellow-100 hover:bg-yellow-100 focus:outline-none focus:shadow-outline-yellow dark:text-yellow-100 dark:bg-yellow-700">
                                <div class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    <span class="ml-2">{{ __('Detener Producción') }}</span>
                                </div>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            @if ($productionPlan->status->name == 'PENDIENTE' || $productionPlan->status->name == 'PRODUCCIÓN DETENIDA')
            <div class="flex justify-end col-span-1 mt-4">
                <a href="{{ route('production-plan.index') }}" class="flex px-10 py-2 items-center justify-center w-full md:w-auto lg:w-auto text-gray-700 text-lg font-semibold leading-tight transition-colors duration-150 bg-gray-200 border border-transparent rounded-lg active:bg-gray-200 hover:bg-gray-200 focus:outline-none focus:shadow-outline-gray dark:bg-gray-600 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                    <span class="ml-4">{{ __('Regresar')}}</span>
                </a>
            </div>
            @endif

        </div>
    </div>
    <script>
        function finalizarProduccion() {
            document.getElementById("finalizarButton").disabled = true;
        }

        function imprimirPDF() {
            document.getElementById("etiquetaButton").disabled = true;


            var productionPlanId = '{{ $productionPlan->id }}';
            var partNumberId = '{{ $productionPlan->partNumber->id }}';

            var printer = "{{ $productionPlan->partNumber->workcenter->printer->ip ?? '' }}";

            if (printer) {
                window.location.href = '{{ route("printipl") }}?productionPlanId=' + productionPlanId + '&partNumberId=' + partNumberId;
            } else {
                var ventanaImpresion = window.open('{{ route("examples") }}?productionPlanId=' + productionPlanId + '&partNumberId=' + partNumberId, '_blank');

                ventanaImpresion.onload = function() {
                    ventanaImpresion.print();
                    // ventanaImpresion.onafterprint = function() {
                    //     ventanaImpresion.close();
                    // };
                    window.location.reload();
                };
            }
        }
    </script>

</x-app-layout>
