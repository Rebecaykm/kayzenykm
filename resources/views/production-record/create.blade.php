<x-app-layout title="Registro de Producción">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
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

        <!-- <form action="{{ route('prodcution-record.store') }}" method="post">
            @csrf -->

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="grid grid-cols-3 gap-4">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Departamento')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" value="{{ $productionPlan->partNumber->workcenter->departament->name }}" disabled />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Proyecto')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" value="{{ $productionPlan->partNumber->projects->implode('model', ', ') }}" disabled />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </div>
                    </div>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Estación')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" value="{{ $productionPlan->partNumber->workcenter->number }} - {{ $productionPlan->partNumber->workcenter->name }}" disabled />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                        </div>
                    </div>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Tipo de SNP')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" value="{{ $productionPlan->partNumber->standardPackage->name }}" disabled />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                    </div>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Cantidad de SNP')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" value="{{ $productionPlan->partNumber->quantity }}" disabled />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                        </div>
                    </div>
                </label>

                <input type="hidden" name="production_plan_id" value="{{ $productionPlan->id }}">
                <input type="hidden" name="part_number_id" value="{{ $productionPlan->partNumber->id }}">

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Número de Parte')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" value="{{ $productionPlan->partNumber->number }}" disabled />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                        </div>
                    </div>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Cantidad Produccida')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" type="number" id="quantity_input" name="quantity" />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.871 4A17.926 17.926 0 003 12c0 2.874.673 5.59 1.871 8m14.13 0a17.926 17.926 0 001.87-8c0-2.874-.673-5.59-1.87-8M9 9h1.246a1 1 0 01.961.725l1.586 5.55a1 1 0 00.961.725H15m1-7h-.08a2 2 0 00-1.519.698L9.6 15.302A2 2 0 018.08 16H8" />
                            </svg>
                        </div>
                    </div>
                    <span id="quantityError" class="hidden text-xs text-red-600 dark:text-red-400">
                        <!-- Mensaje de error se mostrará aquí -->
                    </span>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Hora de Inicio')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" type="datetime-local" id="time_start_input" name="time_start" />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <span id="timeStartError" class="hidden text-xs text-red-600 dark:text-red-400">
                        <!-- Mensaje de error se mostrará aquí -->
                    </span>
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{__('Hora de Fin')}}</span>
                    <!-- focus-within sets the color for the icon when input is focused -->
                    <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                        <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" type="datetime-local" id="time_end_input" name="time_end" />
                        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <span id="timeEndError" class="hidden text-xs text-red-600 dark:text-red-400">
                        <!-- Mensaje de error se mostrará aquí -->
                    </span>
                </label>

            </div>
            <div class="flex justify-end mt-4 gap-4">
                <a href="{{ route('production-plan.index') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray" type="submit">
                    <span>{{ __('Regresar')}}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                </a>
                <button onclick="imprimirPDF()" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" type="submit">
                    <span>{{ __('Guardar')}}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- </form> -->
    </div>
    <script>
        // var pdfData = "{{ session('pdfData') }}";

        // if (pdfData !== "") {
        //     var pdfWindow = window.open("", "_blank", "width=800,height=600,scrollbars=yes");

        //     pdfWindow.document.write(
        //         "<iframe width='100%' height='100%' src='data:application/pdf;base64, " +
        //         encodeURI(pdfData) + "'></iframe>"
        //     );

        //     fetch('/clear-pdf-session-data', {
        //             method: 'GET',
        //             headers: {
        //                 'Content-Type': 'application/json'
        //             }
        //         }).then(response => response.json())
        //         .then(data => console.log(data))
        //         .catch(error => console.error(error));
        // }

        function imprimirPDF() {
            var productionPlanId = '{{ $productionPlan->id }}';
            var partNumberId = '{{ $productionPlan->partNumber->id }}';
            var quantityInput = document.getElementById('quantity_input');
            var timeStartInput = document.getElementById('time_start_input');
            var timeEndInput = document.getElementById('time_end_input');

            // Validación de cantidad
            var quantity = quantityInput.value;
            if (!quantity || isNaN(quantity) || quantity <= 0 || quantity > 10000) {
                // Mostrar mensaje de error y aplicar estilo al input y al span
                quantityError.textContent = 'La cantidad debe ser un número mayor a cero y menor o igual a mil.';
                quantityInput.classList.add('border-red-600');
                quantityError.classList.remove('hidden');
                return; // Salir de la función si la validación no pasa
            } else {
                // Remover el estilo de error y ocultar el span si la validación pasa
                quantityInput.classList.remove('border-red-600');
                quantityError.classList.add('hidden');
            }

            // Validación de hora de inicio
            var timeStart = timeStartInput.value;
            if (!timeStart) {
                timeStartError.textContent = 'La hora de inicio es requerida.';
                timeStartInput.classList.add('border-red-600');
                timeStartError.classList.remove('hidden');
                return;
            } else {
                timeStartInput.classList.remove('border-red-600');
                timeStartError.classList.add('hidden');
            }

            // Validación de hora de fin
            var timeEnd = timeEndInput.value;
            if (!timeEnd || timeEnd <= timeStart) {
                timeEndError.textContent = 'La hora de fin es requerida y debe ser mayor a la hora de inicio.';
                timeEndInput.classList.add('border-red-600');
                timeEndError.classList.remove('hidden');
                return;
            } else {
                timeEndInput.classList.remove('border-red-600');
                timeEndError.classList.add('hidden');
            }


            // Abrir una nueva ventana para la impresión
            var ventanaImpresion = window.open('{{ route("examples") }}?productionPlanId=' + productionPlanId + '&partNumberId=' + partNumberId + '&quantity=' + quantity + '&timeStart=' + timeStart + '&timeEnd=' + timeEnd, '_blank');

            // Esperar a que la ventana se cargue completamente antes de imprimir
            ventanaImpresion.onload = function() {
                ventanaImpresion.print();
                // ventanaImpresion.onafterprint = function() {
                //     ventanaImpresion.close();
                // };
                window.location.reload();
            };
        }
    </script>

</x-app-layout>
