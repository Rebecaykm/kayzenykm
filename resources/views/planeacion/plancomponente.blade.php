<x-app-layout title="Plan">
    <div class=" xl:container lg:container md:container sm:container grid px-6 mx-auto gap-y-2">
        <form method="post" action="{{ route('planeacion.create') }}">
            <h2>
                Partes componentes nnn {{ $tp }}
            </h2>
            @csrf
            <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Dias</span>
                    <input id="dias" name="dias" type="number" max="7" min="1"
                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Fecha inicial</span>
                    <input id="fecha" name="fecha" type="date"
                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <div class="flex justify-center">
                    <button type="submit"
                        class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                        <span class="mr-2">Search</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
        @php
            include_once '../app/Http/Controllers/registros.php';
            $obj = new registros();
        @endphp
        <div class="flex-grow overflow-auto">
            <form action="{{ route('planeacion.update') }}" method="POST">
                @csrf
                <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                <input type="hidden" name="fecha" id="SePC" value={{ $fecha }}>
                <input type="hidden" name="dias" id="SeWC" value={{ $dias }}>
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-800">
                            <th class=" header px-4 py-3 sticky" rowspan="3">No Parte Final </th>
                            <th class=" header px-4 py-3 sticky ">
                                Parte componente
                            </th>
                            <th class=" header px-4 py-3 sticky ">
                                No de parte Finales
                            </th>
                            <th class=" header px-4 py-3 sticky "></th>
                            @php
                                $hoy = $fecha;
                                $dias = 4;
                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                $totalD = 0;
                            @endphp
                            @while ($hoy != $fin)
                                <th aling="center" class="sticky headerpx-4 py-3 text-xs text-center ">
                                    <div class='W-full'>
                                        {{ date('Ymd', strtotime($hoy)) }}
                                    </div>
                                    <div class='w-full'>
                                        {{ date('l', strtotime($hoy)) }}
                                    </div>
                                    <div
                                        class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg border-0 bg-inherit">
                                        <label class="block w-1/2 text-sm border-0 bg-inherit ">
                                            <span class="block  text-xs  form-input ">Dia</span>
                                        </label>
                                        <label class="block  w-1/2 text-sm  border-0 bg-inherit">
                                            <span class="block  text-xs form-input bg-inherit">Noche</span>
                                        </label>
                                    </div>
                                </th>
                                @php
                                    $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                    $totalD = $totalD + 1;
                                @endphp
                            @endwhile
                        </tr>
                    </thead>
                    <tbody
                        class="text-center bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        {{-- ------------------------------------------------------- $plan variable que viene desde controlador  ---------------------------------------------------------- --}}
                        @foreach ($plan as $plans)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-2 py-1 text-xs  bg-teal-300">
                                    {{ $plans->IPROD }}
                                </td>
                                <td class="px-2 py-1 text-xs text-center">
                                </td>
                                <td class="px-2 py-1 text-xs text-center">
                                </td>
                                <td class="px-2 py-1 text-xs text-center ">
                                    <div class='w-40  border  border-b-4 border-rose-600 '>
                                        <input value=" Requeriment (Forecast) "
                                            class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                            disabled />
                                    </div>
                                    <div class='w-40  border  border-b-4 border-rose-600 '>
                                        <input value=" Plan  "
                                            class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                            disabled />
                                    </div>
                                    <div class='w-40  border  border-b-4 border-rose-600 '>
                                        <input value=" Firme  "
                                            class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                            disabled />
                                    </div>
                                </td>
                                @php
                                    $hoy = $fecha;
                                    $datos = $obj->CargarforcastF1($plans->IPROD, $hoy, $dias);
                                @endphp
                                @foreach ($datos as $info)
                                    <td class="px-2 py-1 text-xs text-center  ">
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                                            <label class="block text-sm ">
                                                <input value={{ $info['F' . $hoy . 'D'] }}
                                                    class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>
                                            <label class="block text-sm ">

                                                <input value={{ $info['F' . $hoy . 'D'] }}
                                                    class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>

                                        </div>
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                                            <label class="block text-sm ">

                                                <input value={{ $info['P' . $hoy . 'D'] }}
                                                    class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>
                                            <label class="block text-sm ">

                                                <input value={{ $info['P' . $hoy . 'N'] }}
                                                    class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>

                                        </div>
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                                            <label class="block text-sm ">

                                                <input value={{ $info['Fi' . $hoy . 'D'] }}
                                                    class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>
                                            <label class="block text-sm ">

                                                <input value={{ $info['Fi' . $hoy . 'N'] }}
                                                    class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>

                                        </div>
                                    </td>
                                    @php
                                        $hoy = date('Ymd', strtotime($hoy . '+1 day'));

                                    @endphp
                                @endforeach

                            </tr>
                            {{-- ------------------------------------------------------- busca los subcomponenetes  --------------------------------------------------------------------------------------------------- --}}

                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        <div
            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">
                Show {{ $plan->firstItem() }} - {{ $plan->lastItem() }}
            </span>
            <!-- Pagination -->
            <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                {{ $plan->links() }}

            </span>
        </div>
        <div
            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
            <span class="flex items-center col-span-3">
                Y - TEC KEYLEX MÉXICO
            </span>
            <span class="col-span-2"></span>

            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center">
                    </ul>
                </nav>
            </span>
        </div>
    </div>



    <script>
        function myFunction(xx) {
            console.log(xx);
        }
    </script>

</x-app-layout>
