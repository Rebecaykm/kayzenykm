<x-app-layout title="Planeacion">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Planeación
        </h2>
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Ver planeación
            Finales
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="post" action="{{ route('planeacionview.create') }}">
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              </svg>

                            <span>Ajustar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
           Ver planeación
            Subcomponentes
        </h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="post" action="{{ route('planeacionview.create') }}">
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
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
            <form method="get" action="{{ route('planeacionview.exportsubcomponentes') }}">
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
                    <div class="flex-auto ">
                        <label class="block mt-4 text-sm pt-6 ">

                            <input type="radio" value='1'
                                class="text-purple-600 form-radio focus:border-purple-400  focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                id='Type' name="Type" />
                            <span class="ml-2">Final</span>
                            <br>
                            <input type="radio" id='Type' name="Type" value='2'
                                class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                id='Type' name="Type" />
                            <span class="ml-2">Subcomponentes</span>
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
            <livewire:planning-autocomplete />
        </div>
    </div>


</x-app-layout>
