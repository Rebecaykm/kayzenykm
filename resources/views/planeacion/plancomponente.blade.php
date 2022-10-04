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
                                <th align="center" class="sticky headerpx-4 py-3 text-xs text-center ">
                                    <div class='W-full'>
                                        {{ date('Ymd', strtotime($hoy)) }}
                                    </div>
                                    <div class='w-full'>
                                        {{ date('l', strtotime($hoy)) }}
                                    </div>
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                                        <label class="block text-sm ">
                                            <span
                                                class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">Dia</span>
                                        </label>
                                        <label class="block text-sm ">
                                            <span
                                                class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">Noche</span>
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
                                        <input value=" Requeriment (Forecast)"
                                            class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                            disabled />
                                    </div>

                                </td>
                                @php
                                    $hoy = $fecha;

                                    $cont = $obj->contar($plans->IPROD, $hoy, $fin);
                                    // $conplanq = $obj->contarplan($plans->IPROD, $hoy, $fin);
                                    // $confirme = $obj->contarfirme($plans->IPROD, $hoy, $fin);
                                    // $conshop = $obj->contarShopO($plans->IPROD, $hoy, $fin);
                                    $coni = 0;
                                @endphp
                                @while ($coni < $totalD)
                                    <td class="px-2 py-1 text-xs text-center  ">
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">

                                            @if ($cont != 0)
                                                @php

                                                    $totalD1 = $obj->Forecast($plans->IPROD, $hoy, '%D%');
                                                    $totalD1 = $totalD1 + 0;
                                                    $totalN1 = $obj->Forecast($plans->IPROD, $hoy, '%N%');
                                                    $totalN1 = $totalN1 + 0;
                                                @endphp

                                                <label class="block text-sm ">

                                                    <input value={{ $totalD1 }}
                                                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                </label>
                                                <label class="block text-sm ">

                                                    <input value={{ $totalN1 }}
                                                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                </label>
                                            @else
                                                <label class="block text-sm ">

                                                    <input value='0'
                                                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                </label>
                                                <label class="block text-sm ">

                                                    <input value='0'
                                                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                </label>
                                            @endif
                                        </div>
                                    </td>
                                    @php
                                        $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                        $coni++;
                                    @endphp
                                @endwhile
                            </tr>
                            @php
                                $Sub = $obj->cargar($plans->IPROD);
                            @endphp
                            @foreach ($Sub as $subs)
                                @php
                                    $hoy = $fecha;
                                    $subcomponentes = $obj->Cargarforcast($subs->Componente, $hoy, $dias);
                                @endphp
                                {{-- forecast --}}
                                <tr class="text-gray-700 dark:text-gray-400 ">
                                    <td class="px-2 py-1 text-xs text-center ">
                                    </td>
                                    <td class="px-2 py-1 text-xs text-center">
                                        {{ $subcomponentes['sub'] }}
                                    </td>
                                    <td class="px-2 py-1 text-xs text-center">
                                        @php
                                            $F1sub = $obj->cargarF1($subcomponentes['sub']);
                                        @endphp
                                        @foreach ($F1sub as $F1subs)
                                            {{ $F1subs->Final }}
                                            <br>
                                        @endforeach
                                    </td>
                                    <td class="px-2 py-1 text-xs text-center ">
                                        <div class='w-40  border  border-b-4 border-rose-600 '>
                                            <input value=" Requeriment (Forecast)"
                                                class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </div>

                                        @php
                                            $coni = 0;
                                        @endphp

                                    </td>
                                    @while ($coni < $totalD)
                                        <td class="px-2 py-1 text-xs text-center  ">

                                            {{-- @php
                                                $totalforcastcom = 0;
                                                $totalforcastcomN = 0;
                                            @endphp
                                            {{-- @foreach ($subcomponetes as $subcomponetest) --}}
                                            {{-- {{$subcomponetest['sub']}}/{{$subcomponetest['dia']}}/{{$subcomponetest['valD']}}/{{$subcomponetest['valN']}}

                                            --}} <div
                                                class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                                                <label class="block text-sm ">
                                                    @php

                                                    @endphp

                                                    <input value={{ $subcomponentes['D' . $hoy . 'D'] }}
                                                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                </label>
                                                <label class="block text-sm ">
                                                    @php
                                                        //   dd($subcomponentes['sub'],$subcomponentes,$subcomponentes['20221003D'])
                                                    @endphp
                                                    <input value={{ $subcomponentes['N' . $hoy . 'N'] }}
                                                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                </label>
                                            </div>

                                        </td>

                                        @php
                                            $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                            $coni++;
                                        @endphp
                                    @endwhile

                                </tr>
                            @endforeach
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
