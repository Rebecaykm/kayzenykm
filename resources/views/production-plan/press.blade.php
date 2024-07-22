<x-app-layout title="{{ __('Registro de Consumo de Materiales') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Registro de Consumo de Materiales') }}
        </h2>

        <!-- Botón "Finalizar" -->
        <form action="{{ route('material-consumption.finish') }}" method="post" class="mb-4">
            @csrf
            <input type="hidden" name="productionPlanId" value="{{ $productionPlanId }}">
            <button type="submit" class="flex items-center justify-end w-full md:w-auto px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                <span class="mr-2">{{ __('Finalizar')}}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
        </form>

         <!-- Formulario de Registro el SPM -->
         <form action="{{ route('material-consumption.spm') }}" method="post">
            @csrf
            <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <input type="hidden" name="productionPlanId" value="{{ $productionPlanId }}">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('SPM Real') }}</span>
                    <input name="quantitySmp" type="number" step="any" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Ingrese el SPM" />
                </label>
                <div class="mt-4 text-right">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <span class="mr-2">{{ __('Guardar') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>

        <!-- Formulario de Registro de Consumo de Materiales -->
        <form action="{{ route('material-consumption.store') }}" method="post">
            @csrf
            <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <input type="hidden" name="productionPlanId" value="{{ $productionPlanId }}">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Código de Material') }}</span>
                    <input name="materialCode" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Ingrese el código de material" />
                </label>
                <div class="mt-4 text-right">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <span class="mr-2">{{ __('Guardar') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
