<x-app-layout title="Plan">

    <div class="xl:container lg:container md:container sm:container grid   mx-auto ">
        <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
            <form method="post" action="{{ route('planeacion.create') }}">
                <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        @switch($tp)
                            @case('2,12,123,13,20,23,3')
                                J03W/G
                            @break

                            @case('4,45,47')
                                J59W
                            @break

                            @case('5,56,57')
                                J59J
                            @break

                            @default
                        @endswitch
                    </h2>

                </div>
            </form>
        </div>
        <form action="{{ route('planeacion.updatef1') }}" method="post">
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
                <input type="hidden" name="nextp" id="nextp" value="{{ $partesne }}">
                <input type="hidden" name="paginate" id="paginate" value={{ $pagina + 1 }}>

                <table class="w-full whitespace-no-wrap ">
                    <thead>
                        <tr
                            class=" sticky top-0 text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-800">
                            <th class=" header px-4 py-3 sticky ">Cont</th>

                            <th class=" header px-4 py-3 sticky" rowspan="3">No Parte Final </th>


                            <th class=" header px-4 py-3 sticky "></th>

                            @php
                                $hoy = $fecha;
                                $contp = 0;
                                $totalD = 0;
                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                $diasjava = '';
                            @endphp
                            @while ($hoy != $fin)
                                @if (date('w', strtotime($hoy)) == 0)
                                @else
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
                                @endif
                                @php
                                    $diasjava = $hoy . '/' . $diasjava;
                                    $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                    $totalD = $totalD + 1;
                                @endphp
                            @endwhile
                            <th class=" header px-4 py-3 sticky ">
                                Parte <br> Total
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="text-center bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 dark:text-gray-200">

                        @foreach ($res as $info1)
                            @php

                                $contp += 1;
                                $info = $info1['padre'];
                                $padre = $info['parte'];

                            @endphp
                            @switch($info['WRC'])
                                @case(111020)
                                @case(111030)
                                @case(111010)
                                @case(112020)
                                @case(112010)
                                @case(112040)
                                @case(114010)
                                @case(114020)
                                @case(112030)
                                @case(112060)
                                @case(114030)

                                @break

                                @default
                                    <tr class="text-gray-700 dark:text-gray-400  text-xs ">
                                        @if (strpos($padre, 'SOR') === false)
                                            <td class="px-2 py-1 text-s  bg-teal-300">
                                                {{ $contp }}
                                            </td>

                                            <td class="px-2 py-1 text-xs  bg-teal-300">
                                                <div class="w-20 text-xs dark:border-gray-600 dark:bg-gray-700">
                                                    {{ $padre }}<br>
                                                    SNP {{ $info['Qty'] }}<br>
                                                    WRKcenter {{ $info['WRC'] }}<br>
                                                    Container {{$info['typkt']}}
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-2 py-1 text-s bg-yellow-300">
                                                {{ $contp }}
                                            </td>

                                            <td class="px-2 py-1 text-xs bg-yellow-300">
                                                <div class="w-20 text-xs dark:border-gray-600 dark:bg-gray-700">
                                                    {{ $padre }}<br>
                                                    SNP {{ $info['Qty'] }}<br>
                                                    WRKcenter {{ $info['WRC'] }}
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-2 py-1 text-xs  bg-emerald-100">
                                            <div class="flex flex-row gap-x-3 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">
                                                    <input value="FORECASTE MMVO"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>
                                            </div>
                                            <div class="flex flex-row gap-x-3 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">
                                                    <input value="FIRME MMVO"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>
                                            </div>
                                            <div class="flex flex-row gap-x-3 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">
                                                    <input value="Firme YKM"
                                                        class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                        disabled />
                                                </label>
                                            </div>

                                        </td>

                                        @php
                                            $hoy = $fecha;
                                            $contdias = 0;
                                            $namenA = strtr($padre, ' ', '_');
                                            $totalfirM = 0;
                                            $totalforM = 0;
                                            $totalfirykm = 0;
                                            $valeclD = 0;
                                            $valeclN = 0;
                                            $workcen = $info['WRC'];
                                        @endphp
                                        @while ($contdias < $dias)
                                            @if ($contdias == 7)
                                            @else
                                                <td class="px-2 py-1 text-xs text-center bg-emerald-50 ">
                                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                        <label class="block text-sm ">
                                                            @php


                                                                if (array_key_exists('For' . $hoy . 'D', $info) == false) {
                                                                    $valFD = 0;
                                                                } else {
                                                                    $valFD = $info['For' . $hoy . 'D'];
                                                                }

                                                                if (array_key_exists('For' . $hoy . 'N', $info) == false) {
                                                                    $valFN = 0;
                                                                } else {
                                                                    $valFN = $info['For' . $hoy . 'N'];
                                                                }

                                                                if (array_key_exists('F',$info) == false)
                                                                {

                                                                    $valFiD = $valFD ;
                                                                    $valFiN = $valFN ;
                                                                }else {


                                                                    $firme=$info['F'];

                                                                    if (array_key_exists('F' . $hoy . 'D', $firme) == false) {
                                                                        $valFiD = 0;
                                                                    } else {
                                                                        $valFiD = $firme['F' . $hoy . 'D'];
                                                                    }
                                                                    if (array_key_exists('F' . $hoy . 'N', $firme) == false) {
                                                                        $valFiN = 0;
                                                                    } else {
                                                                        $valFiN = $firme['F' . $hoy . 'N'];
                                                                    }

                                                                }

                                                                if (array_key_exists('ecl' . $hoy . 'D', $info) == false) {
                                                                    $valeclD = 0;
                                                                } else {
                                                                    $valeclD = $info['ecl' . $hoy . 'D'] + 0;
                                                                }
                                                                if (array_key_exists('ecl' . $hoy . 'N', $info) == false) {
                                                                    $valeclN = 0;
                                                                } else {
                                                                    $valeclN = $info['ecl' . $hoy . 'N'] + 0;
                                                                }

                                                                $valRD = 0;
                                                                $valRN = 0;
                                                                $inD = $namenA . '/' . $hoy . '/D/' . $info['WRC'];
                                                                $inN = $namenA . '/' . $hoy . '/N/' . $info['WRC'];
                                                                if ($contdias == 6) {
                                                                    $valFiD = 0;
                                                                    $valFiN = 0;
                                                                }
                                                                $totalforM += $valFD + $valFN;
                                                                $totalfirM += $valeclD + $valeclN;
                                                                $totalfirykm += $valFiN + $valFiD;
                                                                $workcen = $info['WRC'];

                                                            @endphp
                                                            <input value='{{ $valFD }}'
                                                                class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                                disabled />
                                                        </label>
                                                        <label class="block text-sm ">

                                                            <input value='{{ $valFN }}'
                                                                class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                                disabled />
                                                        </label>

                                                    </div>

                                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                        <label class="block text-sm ">

                                                            <input value='{{ $valeclD }}'
                                                                class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                                disabled />
                                                        </label>
                                                        <label class="block text-sm ">

                                                            <input value='{{ $valeclN }}'
                                                                class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                                disabled />
                                                        </label>

                                                    </div>
                                                    {{-- @php
                                                    if ($padre == 'BDTS53383                          ') {
                                                        dd($info1, $valFiD,$valFiN );
                                                    }
                                                @endphp --}}
{{--
                                                    @if (strpos($padre, 'SOR') === false) --}}
                                                        <div
                                                            class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg border-4 border-blue-400">

                                                            <label class="block text-sm ">

                                                                <input id='{{ $inD }}' name='{{ $inD }}'
                                                                    onchange="myFunction('<?php echo $diasjava; ?>', '<?php echo $namenA; ?>','<?php echo $workcen; ?>',this.id)"
                                                                    value='{{ $valFiD }}'
                                                                    class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                            </label>
                                                            <label class="block text-sm ">

                                                                <input id='{{ $inN }}' name='{{ $inN }}'
                                                                    onchange="myFunction('<?php echo $diasjava; ?>', '<?php echo $namenA; ?>','<?php echo $workcen; ?>',this.id)"
                                                                    value='{{ $valFiN }}'
                                                                    class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                            </label>

                                                        </div>

                                                        {{-- @php
                                                            $totalfirykm = 0;
                                                        @endphp --}}
                                                        {{-- <div
                                                            class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg border-4 border-red-400">

                                                            <label class="block text-sm ">

                                                                <input id='{{ $inD }}' name='{{ $inD }}'
                                                                    onchange="myFunction('<?php echo $diasjava; ?>', '<?php echo $namenA; ?>','<?php echo $workcen; ?>',this.id)"

                                                                    class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                                    disabled />
                                                            </label>
                                                            <label class="block text-sm ">

                                                                <input id='{{ $inN }}' name='{{ $inN }}'
                                                                    onchange="myFunction('<?php echo $diasjava; ?>', '<?php echo $namenA; ?>','<?php echo $workcen; ?>',this.id)"

                                                                    class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                                    disabled />
                                                            </label>

                                                        </div> --}}




                                                </td>
                                            @endif


                                            @php
                                                $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                                $contdias++;
                                            @endphp
                                        @endwhile

                                        <td class="px-2 py-1 text-xs  bg-emerald-100">
                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                =
                                                <label class="block text-sm ">
                                                    <input name='totalForMMVO' id='totalForMMVO' value="{{ $totalforM }}"
                                                        class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray bg-green-400 form-input"
                                                        disabled />
                                                </label>
                                            </div>
                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                <label class="block text-sm ">
                                                    <input id='totalFirMMVO' name='totalFirMMVO' value="{{ $totalfirM }}"
                                                        class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray bg-green-400 form-input"
                                                        disabled />
                                                </label>
                                            </div>
                                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                                =
                                                <label class="block text-sm ">
                                                    @php
                                                        $otalphp = 'totalFirykm' . $namenA;
                                                    @endphp
                                                    <input id='{{ $otalphp }}' name='{{ $otalphp }}'
                                                        value="{{ $totalfirykm }}"
                                                        class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray bg-green-400 form-input"
                                                        disabled />
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                            @endswitch
                        @endforeach
                    </tbody>
                </table>

            </div>
        </form>

        <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
            <form method="post" action="{{ route('planeacion.siguiente') }}">
                <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                    @csrf
                    <div class="flex justify-center">
                        <label class="block mt-4 text-sm">
                            <input type="hidden" name={{ $fecha . '/' . $dias }} id="data"
                                value={{ $fecha . '/' . $dias }}>
                            <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                            <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                            <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                            <input type="hidden" name="nextp" id="nextp" value="{{ $partesne }}">
                            <input type="hidden" name="paginate" id="paginate" value={{ $pagina - 1 }}>
                            <input type="hidden" name="fecha" id="data" value={{ $fecha }}>
                            <input type="hidden" name="dias" id="data" value={{ $dias }}>
                        </label>
                    </div>
                    <div class="flex justify-center">
                        @if ($pagina != 0)
                            <button type="submit"
                                class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                                <span class="mr-2">Anterior</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                            </button>
                        @endif
                    </div>
                </div>
            </form>
            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Página</span>
                    <p class="text-blue-600">{{ $pagina }} de {{ $tpag }} </p>
                </label>
            </div>
            <form method="post" action="{{ route('planeacion.siguiente') }}">
                <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                    @csrf
                    <div class="flex justify-center">
                        <label class="block mt-4 text-sm">
                            <input type="hidden" name={{ $fecha . '/' . $dias }} id="data"
                                value={{ $fecha . '/' . $dias }}>
                            <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
                            <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                            <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                            <input type="hidden" name="nextp" id="nextp" value="{{ $partesne }}">
                            <input type="hidden" name="paginate" id="paginate" value={{ $pagina + 1 }}>
                            <input type="hidden" name="fecha" id="data" value={{ $fecha }}>
                            <input type="hidden" name="dias" id="data" value={{ $dias }}>
                        </label>
                    </div>
                    <div class="flex justify-center">
                        @if ($pagina != $tpag)
                            <button type="submit"
                                class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                                <span class="mr-2">Siguiente</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        @else
                            <button type="submit"
                                class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue disabled:opacity-75"
                                disabled="true">
                                <span class="mr-2">Siguiente</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </form>
            <div
                class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Y - TEC KEYLEX MÉXICO
                </span>
                <span class="col-span-2"></span>
                {{-- {{ $res->setPath('/planeacion/create') }} --}}
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                        </ul>
                    </nav>
                </span>
            </div>
        </div>
        <script>
            function myFunction(dias, parte, wc, idtest) {

                let mensaje = dias;
                let arr = mensaje.split('/');
                console.log(parte + '/' + arr[0] + '/D/' + wc, arr)
                val1 = parseInt(document.getElementById(parte + '/' + arr[4] + '/D/' + wc).value);
                val2 = parseInt(document.getElementById(parte + '/' + arr[4] + '/N/' + wc).value);
                val3 = parseInt(document.getElementById(parte + '/' + arr[5] + '/D/' + wc).value);
                val4 = parseInt(document.getElementById(parte + '/' + arr[5] + '/N/' + wc).value);
                val5 = parseInt(document.getElementById(parte + '/' + arr[6] + '/D/' + wc).value);
                val6 = parseInt(document.getElementById(parte + '/' + arr[6] + '/N/' + wc).value);
                val7 = parseInt(document.getElementById(parte + '/' + arr[7] + '/D/' + wc).value);
                val8 = parseInt(document.getElementById(parte + '/' + arr[7] + '/N/' + wc).value);
                val9 = parseInt(document.getElementById(parte + '/' + arr[3] + '/D/' + wc).value);
                val10 = parseInt(document.getElementById(parte + '/' + arr[3] + '/N/' + wc).value);
                val11 = parseInt(document.getElementById(parte + '/' + arr[2] + '/D/' + wc).value);
                val12 = parseInt(document.getElementById(parte + '/' + arr[2] + '/N/' + wc).value);


                valtotal = val1 + val2 + val3 + val4 + val5 + val6 + val7 + val8 + val9 + val10 + val11 + val12;
                document.getElementById('totalFirykm' + parte).value = valtotal;
                console.log(valtotal, document.getElementById('totalFirykm' + parte).value);




            }
        </script>

</x-app-layout>
