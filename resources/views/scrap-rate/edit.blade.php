<x-app-layout title="Editar Tasa de Scrap">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Editar Tasa de Scrap
        </h2>

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

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('scrap-rate.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Fila con los tres inputs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Número de Parte (readonly) -->
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Número de Parte</span>
                        <input
                            name="partNumber"
                            value="{{ $partNumber }}"
                            readonly
                            class="block w-full mt-1 text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 form-input bg-gray-100 cursor-not-allowed" />
                    </label>

                    <!-- Fecha de Inicio (readonly) -->
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Fecha de Inicio</span>
                        <input
                            type="date"
                            name="startDate"
                            value="{{ $startDate }}"
                            required
                            class="block w-full mt-1 text-sm dark:border-gray-700 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input pr-12" />
                    </label>

                    <!-- Tasa de Scrap como porcentaje -->
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Tasa de Scrap (%)</span>
                        <div class="relative">
                            <input
                                type="number"
                                name="scrapRatePercent"
                                value="{{ number_format($scrapRate * 100, 4) }}"
                                step="0.0001"
                                min="0"
                                max="100"
                                required
                                class="block w-full mt-1 text-sm dark:border-gray-700 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input pr-12" />
                        </div>
                    </label>
                </div>

                <!-- Campos ocultos para identificar el registro exacto -->
                <input type="hidden" name="createdDate" value="{{ $createdDate }}">
                <input type="hidden" name="createdTime" value="{{ $createdTime }}">
                <input type="hidden" name="createdUser" value="{{ $createdUser }}">
                <input type="hidden" name="createdWs" value="{{ $createdWs }}">

                <!-- Botones de acción -->
                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('scrap-rate.index') }}" class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-lg active:bg-gray-50 hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
