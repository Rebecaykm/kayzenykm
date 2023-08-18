<x-app-layout title="Planeacion">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Planeación
        </h2>
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Ajustes de planeación
            Finales
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="post" action="{{ route('planeacion.create') }}">
                @csrf
                <input type="hidden" name="Planeacion" id="Planeacion" name="Planeacion" value='1'>
                <div class="flex">
                    <div class="flex-auto mr-3">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Proyecto
                            </span>
                            <select id='SeProject' name='SeProject' onchange='PCenable()'
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray">
                                <option value=''>---Select---</option>
                                <option value='2,12,123,13,20,23,3'>j03G/W</option>
                                <option value='4,45,47'>j59W</option>
                                <option value='5,56,57'>j59J</option>
                            </select>
                        </label>
                    </div>
                    <div class="flex-auto mr-3">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Fecha inicial
                            </span>
                            <input id="fecha" name="fecha" type="date"
                                class="block w-full mt-1  text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                    </div>

                    <div class="flex-auto ">
                        <button
                            class="flex items-center justify-between mt-5 px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>

                            <span>Ajustar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Ajustes de planeación
            Subcomponentes
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="post" action="{{ route('planeacion.create') }}">
                @csrf
                <input type="hidden" name="Planeacion" id="Planeacion" name="Planeacion" value='2'>
                <div class="flex">
                    <div class="flex-auto mr-3">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Proyecto
                            </span>
                            <select id='SeProject' name='SeProject' onchange='PCenable()'
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray">
                                <option value=''>---Select---</option>
                                <option value='2,12,123,13,20,23,3'>j03G/W</option>
                                <option value='4,45,47'>j59W</option>
                                <option value='5,56,57'>j59J</option>
                            </select>
                        </label>
                    </div>
                    <div class="flex-auto mr-3">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Fecha inicial
                            </span>
                            <input id="fecha" name="fecha" type="date"
                                class="block w-full mt-1  text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                    </div>

                    <div class="flex-auto ">
                        <button
                            class="flex items-center justify-between mt-5 px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>

                            <span>Ajustar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Reportes de planeación
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="get" action="{{ route('planeacion.exportsubcomponentes') }}">
                @csrf
                <div class="flex">
                    <div class="flex-auto mr-3">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Proyecto
                            </span>
                            <select id='SeProject' name='SeProject' onchange='PCenable()'
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray">
                                <option value=''>---Select---</option>
                                <option value='2,12,123,13,20,23,3'>j03G/W</option>
                                <option value='4,45,47'>j59W</option>
                                <option value='5,56,57'>j59J</option>
                            </select>
                        </label>
                    </div>
                    <div class="flex-auto mr-3">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Fecha inicial
                            </span>
                            <input id="fecha" name="fecha" type="date"
                                class="block w-full mt-1  text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                    </div>
                    <div class="flex-auto  place-content-cente">
                        <button
                            class="flex items-center justify-between mt-5 px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9.75v6.75m0 0l-3-3m3 3l3-3m-8.25 6a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>

                            <span>Descargar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
        Busqueda individual 
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <livewire:address-autocomplete />
        </div>
    </div>


</x-app-layout>
