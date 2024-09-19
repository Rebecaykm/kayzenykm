<x-app-layout title="{{ __('Reimpresión de Etiqueta') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Reimpresión de Etiqueta') }}
        </h2>

        <div class="grid gap-2 mb-2 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{ __('Número de Parte') }}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $prodcutionRecord->productionPlan->partNumber->number }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Secuencia')}}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $prodcutionRecord->sequence }}
                    </p>
                </div>
            </div>
            <!-- Card -->
            <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                </div>
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                        {{__('Cantidad')}}
                    </p>
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        {{ $prodcutionRecord->quantity }}
                    </p>
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <input type="hidden" id="prodcutionRecordId" name="prodcutionRecordId" value="{{ $prodcutionRecord->id }}">
            <div>
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Motivo de reimpresión') }}</span>
                    <textarea id="printReason" name="printReason" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Ingrese el motivo de reimpresión de etiqueta."></textarea>
                </label>
            </div>
            <div class="flex justify-end mt-2 gap-4">
                <a href="{{ route('back.back') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray">
                    <span>{{ __('Regresar')}}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                </a>
                <button onclick="imprimir()" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    <span>{{ __('Guardar')}}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <script>
        function imprimir() {
            var prodcutionRecordId = '{{ $prodcutionRecord->id }}';
            var printReason = document.getElementById('printReason').value;

            var printer = "{{ $prodcutionRecord->productionPlan->partNumber->workcenter->printer->ip ?? '' }}"

            if (printer) {
                window.location.href = '{{ route("prodcution-record.store-reprint-ip") }}?prodcutionRecordId=' + encodeURIComponent(prodcutionRecordId) + '&printReason=' + encodeURIComponent(printReason);
            } else {
                var url = '{{ route("prodcution-record.store-reprint") }}' + '?prodcutionRecordId=' + encodeURIComponent(prodcutionRecordId) + '&printReason=' + encodeURIComponent(printReason);

                var ventanaImpresion = window.open(url);

                ventanaImpresion.onload = function() {
                    ventanaImpresion.print();
                    window.location.reload();
                };
            }
        }
    </script>
</x-app-layout>
