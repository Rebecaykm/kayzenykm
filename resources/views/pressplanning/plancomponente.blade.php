<x-app-layout title="Plan">
    <div class="xl:container lg:container md:container sm:container grid   mx-auto ">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        </h2>
    </div>
    <div class="flex-grow overflow-auto sm:h-80 md:h-96 lg:h-screen xl:h-screen">
        <input type="hidden" name={{ $fecha . '/' . $dias }} id="data" value={{ $fecha . '/' . $dias }}>



        <table class="w-full whitespace-no-wrap ">
            <thead>
                <tr
                    class=" sticky top-0 text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-800">
                    <th class=" header px-4 py-3 sticky" rowspan="3">No Parte Final </th>
                    <th class=" header px-4 py-3 sticky" rowspan="3">Última orden </th>
                    @php
                        $hoy = $fecha;
                        $totalD = 0;
                        $tdias = $dias;
                        $dias = $dias - 1;
                        $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                        $diasjava = '';
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
                                    Plan
                                </label>

                                <label
                                    class="block w-20 gap-x-2 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                                    Real
                                </label>
                            </div>
                        </th>
                        @php
                            $diasjava = $hoy . '/' . $diasjava;
                            $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                            $totalD = $totalD + 1;

                        @endphp
                    @endwhile

                </tr>
            </thead>
            <tbody class="text-center bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 dark:text-gray-200">

                @foreach ($res as $infonum)
                    @php
                        $info = $infonum['Mat'];
                        $orden = $info['ultimaorden'];
                        $semana = $info['semana'];

                    @endphp
                    <tr class="text-gray-700 dark:text-gray-400   text-xs ">
                        <td class="px-2 py-1 text-xs bg-teal-300 ">
                            {{ $info['mat'] }}
                            <br>
                            Consumo:{{floatval($orden['Peso'])}}
                            <br>
                            Clase:{{$orden['Clase']}}
                        </td>
                        <td class="px-2 py-1 text-xs  ">
                            <div>
                                Fecha: {{ $orden['Fecha'] }}
                            </div>
                            <div>
                                Número de orden {{ $orden['orden'] }}
                            </div>
                            @php
                                $p=$orden['R'];
                                $f=$orden['F'];

                            @endphp
                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                <label
                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                                    {{  intval($p)}}
                                </label>
                                <label
                                    class="block w-20 gap-x-2 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                                    {{ intval($f) }}
                                </label>
                            </div>
                        </td>
                        @php
                            $hoy = $fecha;
                            $contdias = 0;

                        @endphp
                        @while ($contdias < $dias)
                            <td class="px-2 py-1 text-xs text-center bg-emerald-50 ">
                                @if (array_key_exists($hoy, $semana) != false)
                                    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                        <label class="block text-sm ">
                                            @php
                                                $dat = $semana[$hoy];
                                                if (array_key_exists('R', $dat) == false) {
                                                    $valFD = '0';
                                                } else {
                                                    $valFD = $dat['R'];
                                                }
                                                if (array_key_exists('F', $dat) == false) {
                                                    $valFN = '0';
                                                } else {
                                                    $valFN = $dat['F'];
                                                }
                                            @endphp
                                            <input value={{ intval($valFD) }}
                                                class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </label>
                                        <label class="block text-sm ">
                                            <input value={{ intval($valFN) }}
                                                class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                disabled />
                                        </label>
                                    </div>
                            </td>
                        @endif
                        @php
                            $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                            $contdias++;
                        @endphp
                @endwhile
                </tr>
                @foreach ($infonum['hijo'] as $info1)
                    @php
                        $par = $info1['parte'];
                        $orden = $info1['ultimaorden'];
                        $semana = $info1['semana'];

                    @endphp

                    <tr class="text-gray-700 dark:text-gray-400   text-xs ">
                        <td class="px-2 py-1 text-xs  ">
                            {{ $par }}
                        </td>
                        <td class="px-2 py-1 text-xs  ">
                            <div>
                                Fecha: {{ $orden['Fecha'] }}
                                @php
                                    $p=$orden['R'] ;
                                   $f= $orden['F']
                                @endphp
                            </div>
                            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                <label
                                    class="block w-20 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                                    {{ intval($p) }}
                                </label>
                                <label
                                    class="block w-20 gap-x-2 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                                    {{intval($f) }}
                                </label>
                            </div>
                        </td>
                        @php
                            @$ban = 1;
                        @endphp

                    @php
                        $hoy = $fecha;
                        $contdias = 0;

                    @endphp
                    @while ($contdias < $dias)
                        <td class="px-2 py-1 text-xs text-center bg-emerald-50 ">
                            @if (array_key_exists($hoy, $semana) != false)
                                <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                                    <label class="block text-sm ">
                                        @php
                                            $dat = $semana[$hoy];
                                            if (array_key_exists('R', $dat) == false) {
                                                $valFD = '0';
                                            } else {
                                                $valFD = $dat['R'];
                                            }
                                            if (array_key_exists('F', $dat) == false) {
                                                $valFN = '0';
                                            } else {
                                                $valFN = $dat['F'];
                                            }
                                        @endphp
                                        <input value={{ intval($valFD) }}
                                            class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                            disabled />
                                    </label>
                                    <label class="block text-sm ">
                                        <input value={{ intval($valFN) }}
                                            class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                            disabled />
                                    </label>
                                </div>
                        </td>
                    @endif
                    @php
                            $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                            $contdias++;
                        @endphp
                    @endwhile
                </tr>
                @endforeach
                @endforeach

            </tbody>
        </table>

    </div>




</x-app-layout>
