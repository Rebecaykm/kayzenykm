<x-app-layout title="Plan">
    <div class="container">
        <div class="container">
            <div class="container grid px-6 mx-auto gap-y-2">

                {{-- <form method="post" action="{{ route('planeacion.create') }}">
                    @csrf
                    <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Tipo de parte
                            </span>
                            <select id='SeTP' name='SeTP' onchange='PCenable()'
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                @switch($tp)
                                    @case(1)
                                        <option value='1' selected="selected">Partes finales</option>
                                    @break

                                    @case(2)
                                        <option value='2' selected="selected">Partes componentes</option>
                                    @break

                                    @default
                                        <option value=''>---Select---</option>
                                        <option value='1'>Partes finales</option>
                                        <option value='2'>Partes componentes</option>
                                @endswitch

                            </select>
                        </label>
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Codigo planeador
                            </span>
                            <select id='SePC' name='SePC' onchange='WCenable()' disabled='true'
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                @if (is_object($ipb))
                                    <option value=''>---Select---</option>
                                    @foreach ($ipb as $PlannerC)
                                        <option value='{{ $PlannerC->PBPBC }}'>
                                            {{ $PlannerC->PBPBC }}//{{ $PlannerC->PBNAM }}</option>
                                    @endforeach
                                @else
                                    <option selected="selected" value={{ $ipb }}>{{ $ipb }}</option>
                                @endif

                            </select>
                        </label>
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Centro de costos
                            </span>
                            <select id='SeWC' name='SeWC'
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                disabled='true'>
                                @if (is_object($LWK))
                                    <option value=''>---Select---</option>
                                    <option value='1'>Todos</option>
                                    @foreach ($LWK as $WCss)
                                        <option value={{ $WCss->WWRKC }}>{{ $WCss->WWRKC }}//{{ $WCss->WDESC }}
                                        </option>
                                    @endforeach
                                @else
                                    <option selected="selected" value={{ $LWK }}>{{ $LWK }}</option>
                                @endif
                            </select>
                        </label>

                        <div class="flex justify-center">
                            <button type="submit"
                                class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                                <span class="mr-2">Search</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </form> --}}
                <form method="post" action="{{ route('planeacion.create') }}">
                    @csrf
                    <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                        <input type="text" name="nombre" id="SeTP" value={{$tp}}>
                        <input type="text" name="nombre" id="SePC" value={{$cp}}>
                        <input type="text" name="nombre" id="SeWC" value={{$wc}}>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if ($tp != 'NO')
            <div class="container">
                @php
                    include_once '../app/Http/Controllers/registros.php';
                    $obj = new registros();
                @endphp
                <div class="container">
                    <div class="flex flex-col h-screen">
                        <div class="flex-grow overflow-auto h-96">
                            <table class="relative w-full border">
                                <thead class='fija'>
                                    <tr
                                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800 sticky top-0">
                                        <th class=" header px-4 py-3 sticky" rowspan="4">Part No
                                        <th class=" header px-4 py-3 sticky "></th>
                                        <th class=" header px-4 py-3 sticky colmde"></th>
                                        @php
                                            $hoy = $fecha;
                                            $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                            $totalD = 0;
                                        @endphp

                                        @while ($hoy != $fin)
                                            <th colspan="2" align="center"
                                                class="sticky headerpx-4 py-3 text-xs text-center colmde">
                                                {{ date('Ymd', strtotime($hoy)) }}
                                                <br>
                                                {{ date('l', strtotime($hoy)) }}
                                            </th>
                                            @php
                                                $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                                $totalD = $totalD + 1;
                                            @endphp
                                        @endwhile
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    <tr>
                                        <td class="px-4 py-3 text-xs text-center">
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center">
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde ">
                                        </td>
                                        @php
                                            $coni = 0;
                                        @endphp
                                        @while ($coni < $totalD)
                                            <td class="px-4 py-3 text-xs text-center ">
                                                D
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center colmde ">
                                                N
                                            </td>
                                            @php
                                                $coni++;
                                            @endphp
                                        @endwhile
                                    </tr>
                                    @foreach ($plan as $plans)
                                        <tr class="text-gray-700 dark:text-gray-400">
                                            <td class="px-4 py-3 text-xs text-center ">
                                                {{ $plans->IPROD }}

                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                            </td>
                                            <td class='px-4 py-3 text-xs text-center colmde'>
                                            </td>
                                            @php
                                                $coni = 0;
                                            @endphp
                                            @while ($coni < $totalD)
                                                <td class="px-4 py-3 text-xs text-center ">
                                                </td>
                                                <td class="px-4 py-3 text-xs text-center colmde  ">
                                                </td>
                                                @php
                                                    $coni++;
                                                @endphp
                                            @endwhile
                                        </tr>
                                        {{-- forecast --}}
                                        <tr class="text-gray-700 dark:text-gray-400">
                                            <td class="px-4 py-3 text-xs text-center">
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                                Parte final:<br>
                                                {{ $plans->BCLAC }} /{{ $plans->BCHLD }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center colmde">
                                                Requeriment (Forecast)
                                            </td>
                                            @php
                                                $cont = $obj->contar($plans->IPROD, $hoy, $fin);
                                            @endphp
                                            @if ($cont != 0)
                                                @php
                                                    $hoy = $fecha;
                                                    $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                @endphp
                                                @while ($hoy != $fin)
                                                    <td class="px-4 py-3 text-xs text-center  ">
                                                        @php
                                                            $QTY = $obj->contard($plans->IPROD, $hoy, '%D%');
                                                        @endphp
                                                        @if ($QTY != 0)
                                                            @php
                                                                $QTY = $obj->Forecast($plans->IPROD, $hoy, '%D%');
                                                                $totalD1 = 0;
                                                            @endphp
                                                            @foreach ($QTY as $QTYs)
                                                                @php
                                                                    $totalD1 = $totalD1 + $QTYs->MQTY;
                                                                @endphp
                                                            @endforeach
                                                            {{ $totalD1 }}
                                                        @else
                                                            0 <br>
                                                        @endif

                                                    </td>
                                                    <td class="px-4 py-3 text-xs text-center  colmde ">
                                                        @php
                                                            $QTY = $obj->contard($plans->IPROD, $hoy, '%N%');
                                                        @endphp
                                                        @if ($QTY != 0)
                                                            @php
                                                                $QTY = $obj->Forecast($plans->IPROD, $hoy, '%N%');
                                                                $totaln1 = 0;
                                                            @endphp
                                                            @foreach ($QTY as $QTYs)
                                                                @php
                                                                    $totaln1 = $totaln1 + $QTYs->MQTY;
                                                                @endphp
                                                            @endforeach
                                                            {{ $totaln1 }}
                                                        @else
                                                            0 <br>
                                                        @endif

                                                    </td>
                                                    @php
                                                        $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                                    @endphp
                                                @endwhile
                                            @else
                                                @php
                                                    $coni = 0;
                                                @endphp
                                                @while ($coni < $totalD)
                                                    <td class="px-4 py-3 text-xs text-center ">
                                                        0
                                                    </td>
                                                    <td class="px-4 py-3 text-xs text-center colmde  ">
                                                        0
                                                    </td>
                                                    @php
                                                        $coni++;
                                                    @endphp
                                                @endwhile
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3 text-xs text-center">
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">

                                            </td>
                                            <td class="px-4 py-3 text-xs text-center colmde">
                                                Cantidad de plan
                                            </td>
                                            @php
                                                $hoy = $fecha;
                                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                $conplanq = $obj->contarplan($plans->IPROD, $hoy, $fin);
                                                $coni = 0;
                                            @endphp
                                            @if ($conplanq == 0)
                                                @while ($coni < $totalD)
                                                    <td class="px-4 py-3 text-xs text-center ">
                                                        0x
                                                    </td>
                                                    <td class="px-4 py-3 text-xs text-center colmde  ">
                                                        0y
                                                    </td>
                                                    @php
                                                        $coni++;
                                                    @endphp
                                                @endwhile
                                            @else
                                                @while ($hoy != $fin)
                                                    <td class="px-4 py-3 text-xs text-center  ">
                                                        @php
                                                            $totalplan = 0;
                                                            $planq = $obj->plan($plans->IPROD, $hoy, '%D%');
                                                        @endphp
                                                        @foreach ($planq as $plansq)
                                                            @php
                                                                $totalplan = $totalplan + $plansq->FQTY;
                                                            @endphp
                                                        @endforeach
                                                        {{ $totalplan }}
                                                    </td>
                                                    <td class="px-4 py-3 text-xs text-center  colmde ">
                                                        @php
                                                            $totalplan = 0;
                                                            $planq = $obj->plan($plans->IPROD, $hoy, '%N%');
                                                        @endphp
                                                        @foreach ($planq as $plansq)
                                                            @php
                                                                $totalplan = $totalplan + $plansq->FQTY;
                                                            @endphp
                                                        @endforeach
                                                        {{ $totalplan }}
                                                    </td>
                                                    @php
                                                        $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                                    @endphp
                                                @endwhile
                                            @endif
                                        <tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                </div>
            </div>
        @endif

        <script>
            function WCenable() {
                if (this.value != '') {
                    document.getElementById("SeWC").disabled = false;
                } else {
                    document.getElementById("SeWC").disabled = true;
                }
            };
        </script>
        <script>
            function PCenable() {
                if (this.value != '') {
                    document.getElementById("SePC").disabled = false;
                } else {
                    document.getElementById("SePC").disabled = true;
                }
            };
        </script>
</x-app-layout>
