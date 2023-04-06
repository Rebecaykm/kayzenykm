<x-app-layout title="Plan">
    {{-- @php
        include_once '../app/Http/Controllers/registros.php';
        $obj = new registros();
        $projecto = $obj->Projecto($tp);
        // $dias = ;
    @endphp --}}
    <div class="xl:container lg:container md:container sm:container grid   mx-auto ">
        <form method="post" action="{{ route('planeacion.create') }}">
            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Planeación
                </h2>
                @csrf
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Dias</span>
                    <input id="dias" name="dias" type="number" max="7" min="1"
                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <div class="flex justify-center">
                    <label class="block text-sm ">
                        <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                        <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                        <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                        <span class="text-gray-700 dark:text-gray-400 text-xs">Fecha inicial</span>
                        <input id="fecha" name="fecha" type="date"
                            class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                    </label>
                </div>
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

        <form method="get" action="{{ route('planeacion.export') }}">
            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                <div class="flex justify-center">
                    {{-- <span class="text-gray-700 dark:text-gray-400">
                        <input type="hidden" name="Fecha" id="Fecha" value={{ $fecha  }}>
                    </span> --}}
                    <div class="flex justify-center m-2">
                        <label class="block text-sm ">
                            <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                            <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                            <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                            <span class="text-gray-700 dark:text-gray-400 text-xs">Fecha inicial</span>
                            <input id="fecha" name="fecha" type="date"
                                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                    </div>
                    <div class="flex justify-center m-2">
                        <label class="block text-sm ">
                            <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                            <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                            <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                            <span class="text-gray-700 dark:text-gray-400 text-xs">Fecha Fin</span>
                            <input id="fechaFin" name="fechaFin" type="date"
                                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                    </div>
                    <button
                    class="flex items-center justify-between px-4  text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                    Reporte Excel
                    </button>
                </div>
            </div>
        </form>


        <form action="{{ route('planeacion.update') }}" method="post">
            <div class="flex flex-row gap-x-4  items-center p-0 rounded-lg">
                @csrf
                <div class="flex justify-center">
                    <button type="submit"
                        class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        <span class="mr-2">Actualizar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex-grow overflow-auto sm:h-80 md:h-96 lg:h-screen xl:h-screen">
                <input type="hidden" name={{ $fecha . '/' . $dias }} id="data" value={{ $fecha . '/' . $dias }}>
                <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                <table class="w-full whitespace-no-wrap ">
                    <thead>
                        <tr
                            class=" sticky top-0 text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-800">
                            <th class=" header px-4 py-3 sticky" rowspan="3">No Parte Final </th>
                            <th class=" header px-4 py-3 sticky ">
                                Parte <br> componente
                            </th>
                            <th class=" header px-4 py-3 sticky ">
                                Parte <br> Finales
                            </th>
                            <th class=" header px-4 py-3 sticky "></th>
                            @php
                                $hoy = $fecha;
                                $totalD = 0;
                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                            @endphp
                            @while ($hoy != $fin)
                                <th aling="center" class="sticky headerpx-4 py-3 text-xs text-center ">
                                    <div class='W-full'>
                                        {{ date('Ymd', strtotime($hoy)) }}
                                    </div>
                                    <div class='w-full'>
                                        {{ date('l', strtotime($hoy)) }}
                                    </div>
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                        <label
                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                                            Dia
                                        </label>
                                        <label
                                            class="block w-20 gap-x-2 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                                            Noche
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
                        @foreach ($plan as $info)
                        @php
                            dd($plan)
                        @endphp
                            <tr class="text-gray-700 dark:text-gray-400  text-xs ">
                                <td class="px-2 py-1 text-xs  bg-teal-300">
                                    {{ $info['parte'] }}
                                    {{-- @php
                                        $infoP = $obj->info($info['parte']);

                                    @endphp --}}
                                    <div class="w-20 text-xs dark:border-gray-600 dark:bg-gray-700">
                                        {{-- {{ $infoP['IMBOXQ'] }} --}}
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-xs  bg-emerald-100">
                                </td>
                                <td class="px-2 py-1 text-xs  bg-emerald-100">
                                </td>
                                <td class="px-2 py-1 text-xs  bg-emerald-100">
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                        <label class="block text-sm ">
                                            <input value="Requeriment (Forecast)"
                                                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </label>
                                    </div>
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                        <label class="block text-sm ">
                                            <input value="Requeriment (Parent parts)"
                                                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </label>
                                    </div>
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                        <label class="block text-sm ">

                                            <input value="Plan"
                                                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </label>
                                    </div>
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                        <label class="block text-sm ">

                                            <input value="Firme"
                                                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </label>
                                    </div>
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                        <label class="block text-sm ">

                                            <input value="Shope Order"
                                                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </label>
                                    </div>
                                </td>
                                @php
                                    $hoy = $fecha;
                                    $contdias = 0;
                                @endphp
                                @while ($contdias < $dias)
                                    <td class="px-2 py-1 text-xs text-center bg-emerald-50 ">
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                            <label class="block text-sm ">
                                                @php

                                                    if (array_key_exists('F' . $hoy . 'D', $info) == false) {
                                                        $valFD = '-';
                                                    } else {
                                                        $valFD = $info['F' . $hoy . 'D'];
                                                    }

                                                    if (array_key_exists('F' . $hoy . 'N', $info) == false) {
                                                        $valFN = '-';
                                                    } else {
                                                        $valFN = $info['F' . $hoy . 'N'];
                                                    }
                                                    $var = 'R' . $hoy . 'D';
                                                    if (array_key_exists($var, $info) == false) {
                                                        $valRD = '-';
                                                    } else {
                                                        $valRD = $info['R' . $hoy . 'D'];
                                                    }

                                                    if (array_key_exists('R' . $hoy . 'N', $info) == false) {
                                                        $valRN = '-';
                                                    } else {
                                                        $vaRN = $info['R' . $hoy . 'N'];
                                                    }

                                                    if (array_key_exists('P' . $hoy . 'D', $info) == false) {
                                                        $valPD = '-';
                                                    } else {
                                                        $valPD = $info['P' . $hoy . 'D'];
                                                    }
                                                    if (array_key_exists('P' . $hoy . 'N', $info) == false) {
                                                        $valPN = '-';
                                                    } else {
                                                        $valPN = $info['P' . $hoy . 'N'];
                                                    }

                                                    if (array_key_exists('Fi' . $hoy . 'D', $info) == false) {
                                                        $valFiD = 0;
                                                    } else {
                                                        $valFiD = $info['Fi' . $hoy . 'D'];
                                                    }
                                                    if (array_key_exists('Fi' . $hoy . 'N', $info) == false) {
                                                        $valFiN = 0;
                                                    } else {
                                                        $valFiN = $info['Fi' . $hoy . 'N'];
                                                    }

                                                    if (array_key_exists('S' . $hoy . 'D', $info) == false) {
                                                        $valSD = '-';
                                                    } else {
                                                        $valSD = $info['S' . $hoy . 'D'];
                                                    }

                                                    if (array_key_exists('S' . $hoy . 'N', $info) == false) {
                                                        $valSN = '-';
                                                    } else {
                                                        $valSN = $info['S' . $hoy . 'N'];
                                                    }
                                                    // $valRD = $info['R' . $hoy . 'D'];
                                                    // $valRN = $info['R' . $hoy . 'N'];
                                                    $valRD=0;
                                                    $valRN=0;

                                                @endphp
                                                <input value={{ $valFD }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />
                                            </label>
                                            <label class="block text-sm ">

                                                <input value={{ $valFN }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />
                                            </label>

                                        </div>
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                            <label class="block text-sm ">

                                                <input value={{ $valRD }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />

                                            </label>
                                            <label class="block text-sm ">

                                                <input value={{ $valRN }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />

                                            </label>
                                        </div>
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                            <label class="block text-sm ">

                                                <input value={{ $valPD }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />
                                            </label>
                                            <label class="block text-sm ">

                                                <input value={{ $valPN }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />
                                            </label>

                                        </div>
                                        @php
                                            $namenA = strtr($info['parte'], ' ', '_');
                                            $inD = $namenA . '/' . $hoy . '/D';
                                            $inN = $namenA . '/' . $hoy . '/N';
                                        @endphp
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                            <label class="block text-sm ">

                                                <input id={{ $inD }} name={{ $inD }}
                                                    value={{ $valFiD }} onclick='myFunction(this.id)'
                                                    type="number" min="0"
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>
                                            <label class="block text-sm ">

                                                <input id={{ $inD }} name={{ $inN }}
                                                    value={{ $valFiN }} onclick='myFunction(this.id)'
                                                    type="number" min="0"
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                            </label>

                                        </div>
                                        <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                            <label class="block text-sm ">

                                                <input value={{ $valSD }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />
                                            </label>
                                            <label class="block text-sm ">

                                                <input value={{ $valSN }}
                                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                    disabled />
                                            </label>

                                        </div>
                                    </td>
                                    @php
                                        $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                        $contdias++;
                                    @endphp
                                @endwhile

                            </tr>
                            {{-- ------------------------------------------------------- busca los subcomponenetes  --------------------------------------------------------------------------------------------------- --}}
                            @php
                                $contsub = $obj->contcargar($info['parte']);
                                $hijos = 'hijos' . $info['parte'];
                            @endphp
                            @if (array_key_exists($hijos, $info))
                                @php
                                    $hoy = $fecha;
                                    // $datossub = $obj->Cargarforcast($info['parte'], $hoy, $dias);

                                    // $Sub = $obj->cargar($plans->IPROD);
                                    $datossub = $info[$hijos];

                                @endphp

                                @foreach ($datossub as $datossubs)
                                    @php
                                        $hoy = $fecha;

                                    @endphp
                                    <tr class="text-gray-700 dark:text-gray-400 ">
                                        <td class="px-2 py-1 text-xs text-center ">
                                        </td>
                                        <td class="px-2 py-1 text-xs text-center">
                                            {{ $datossubs['sub'] }}
                                            @php
                                                // $infoP = $obj->info($datossubs['sub']);

                                            @endphp
                                            {{-- <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">
                                                    SNP
                                                    <input value={{ $infoP['IMBOXQ'] }}
                                                        class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>
                                            </div> --}}
                                        </td>

                                        <td class="px-2 py-1 text-xs text-center">
                                            @php
                                                $F1sub = $obj->cargarF1($datossubs['sub']);
                                            @endphp
                                            @foreach ($F1sub as $F1subs)
                                                {{ $F1subs['final'] }}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td class="px-2 py-1 text-xs text-center ">

                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">
                                                    <input value="Requeriment (Forecast)"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>


                                            </div>
                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">
                                                    <input value="Requeriment (Parent parts)"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>


                                            </div>
                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">

                                                    <input value="Plan"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>


                                            </div>
                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">

                                                    <input value="Firme"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>


                                            </div>
                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">

                                                    <input value="Shope Order"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>


                                            </div>

                                        </td>
                                        @php
                                            $coni = 0;
                                            $hoy1 = $fecha;
                                        @endphp
                                        @while ($coni < $dias)
                                            @php
                                                if (array_key_exists('F' . $hoy1 . 'D', $datossubs) == false) {
                                                    $valFDH = '-';
                                                } else {
                                                    $valFDH = $datossubs['F' . $hoy1 . 'D'];
                                                }

                                                if (array_key_exists('F' . $hoy1 . 'N', $datossubs) == false) {
                                                    $valFNH = '-';
                                                } else {
                                                    $valFNH = $datossubs['F' . $hoy1 . 'N'];
                                                }
                                                $var = 'R' . $hoy . 'D';
                                                if (array_key_exists('R' . $hoy1 . 'D', $datossubs) == false) {
                                                    $valRDH = '-';
                                                } else {
                                                    $valRDH = $datossubs['R' . $hoy1 . 'D'];
                                                }

                                                if (array_key_exists('R' . $hoy1 . 'N', $datossubs) == false) {
                                                    $valRNH = '-';
                                                } else {
                                                    $valRNH = $datossubs['R' . $hoy1 . 'N'];
                                                }

                                                if (array_key_exists('P' . $hoy1 . 'D', $datossubs) == false) {
                                                    $valPDH = '-';
                                                } else {
                                                    $valPDH = $datossubs['P' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('P' . $hoy1 . 'N', $datossubs) == false) {
                                                    $valPNH = '-';
                                                } else {
                                                    $valPNH = $datossubs['P' . $hoy1 . 'N'];
                                                }

                                                if (array_key_exists('Fi' . $hoy1 . 'D', $datossubs) == false) {
                                                    $valFiDH = 0;
                                                } else {
                                                    $valFiDh = $datossubs['Fi' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('Fi' . $hoy1 . 'N', $datossubs) == false) {
                                                    $valFiNH = 0;
                                                } else {
                                                    $valFiNH = $datossubs['Fi' . $hoy1 . 'N'];
                                                }

                                                if (array_key_exists('S' . $hoy1 . 'D', $datossubs) == false) {
                                                    $valSDH = '-';
                                                } else {
                                                    $valSDH = $datossubs['S' . $hoy1 . 'D'];
                                                }

                                                if (array_key_exists('S' . $hoy1 . 'N', $datossubs) == false) {
                                                    $valSNH = '-';
                                                } else {
                                                    $valSNH = $datossubs['S' . $hoy1 . 'N'];
                                                }

                                            @endphp
                                            <td class="px-2 py-1 text-xs text-center  ">
                                                <div
                                                    class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                    <label class="block text-sm ">
                                                        <input value={{ $valFDH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                    <label class="block text-sm ">
                                                        <input value={{ $valFNH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                </div>
                                                <div
                                                    class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                    <label class="block text-sm ">

                                                        <input value={{ $valRDH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                    <label class="block text-sm ">

                                                        <input value={{ $valRNH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                </div>
                                                <div
                                                    class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                    <label class="block text-sm ">
                                                        <input value={{ $valPDH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                    <label class="block text-sm ">
                                                        <input value={{ $valPNH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                </div>
                                                @php
                                                    $namenA = strtr($datossubs['sub'], ' ', '_');
                                                    $inD = $namenA . '/' . $hoy1 . '/D';
                                                    $inN = $namenA . '/' . $hoy1 . '/N';
                                                @endphp
                                                <div
                                                    class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                    <label class="block text-sm ">
                                                        <input id={{ $inD }} name={{ $inD }}
                                                            value={{ $valFiDH }} onclick='myFunction(this.id)'
                                                            type="number" min="0"
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                    </label>
                                                    <label class="block text-sm ">
                                                        <input id={{ $inN }} name={{ $inN }}
                                                            value={{ $valFiNH }} onclick='myFunction(this.id)'
                                                            type="number" min="0"
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                    </label>
                                                </div>
                                                <div
                                                    class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                    <label class="block text-sm ">
                                                        <input value={{ $valSDH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                    <label class="block text-sm ">
                                                        <input value={{ $valSNH }}
                                                            class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                            disabled />
                                                    </label>
                                                </div>

                                            </td>
                                            @php
                                                $hoy1 = date('Ymd', strtotime($hoy1 . '+1 day'));
                                                $coni++;
                                            @endphp
                                        @endwhile
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>

            </div>
        </form>
        <div
        class="grid px-4 text-xs font-semibold tracking-wide text-gray-500 uppercase  dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
        <span class="flex items-center col-span-3">
            <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
            Show {{ $plantotal->firstItem() }} - {{ $plantotal->lastItem() }}
        </span>
        <!-- Pagination -->
        <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
            {{ $plantotal->withQueryString()->appends(['SeProject' => $tp])->links() }}
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
            console.log(xx, document.getElementById(xx).id);
        }
    </script>

</x-app-layout>
