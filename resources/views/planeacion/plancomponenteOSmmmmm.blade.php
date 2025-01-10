<x-app-layout title="Plan">

    <form action="{{ route('planeacionOS.update') }}" method="post">
        @csrf
        <input type="hidden" name={{ $fecha . '/' . $dias }} id="data" value={{ $fecha . '/' . $dias }}>
        <input type="hidden" name="SeProject" id="SeProject" value={{ $tp }}>
        <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
        <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
        <input type="hidden" name="nextp" id="nextp" value="{{ $partesne }}">
        <input type="hidden" name="paginate" id="paginate" value={{ $pagina + 1 }}>
        <div class="xl:container lg:container md:container sm:container grid   mx-auto ">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Planeacion proyecto
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
        <div class="flex justify-center">
            <button type="submit"
                class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                <span class="mr-2">Actualizar</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </button>
        </div>
        <div class="flex-grow overflow-auto sm:h-80 md:h-96 lg:h-screen xl:h-screen">

            <div class="flex flex-col max-w-full overflow-x-auto">
                @php
                    $tdias = $dias;
                @endphp

            </div>
            <!-- Content -->
            @foreach ($res as $info1)
                @php
                    $info = $info1['padre'];
                @endphp


                <div class="flex flex-col">

                    <div class="flex items-start">
                        <!-- Info -->
                        <div class="w-48 bg-blue-50 p-4 border-r">
                            <div class="text-sm text-gray-500">Item</div>
                            <div class="font-bold">{{ $info['parte'] }}</div>
                            <div class="text-sm text-gray-500">SNP: 80,000</div>
                            <div class="text-sm text-gray-500">Workcenter: 124400</div>

                        </div>

                        <!-- Contenedor para el scroll horizontal -->
                        <div class="flex-1 overflow-x-auto">
                            <div class="flex whitespace-nowrap">
                                @php
                                    $hoy = $fecha;
                                    $totalD = 0;

                                    $Xdias = $tdias - 2;
                                    $fin = date('Ymd', strtotime($hoy . '+' . $Xdias . ' day'));
                                    $diasjava = '';
                                @endphp

                                @php
                                    $histo = [];
                                    $datossub = $info1['hijos'];
                                @endphp
                            </div>
                        </div>
                    </div>
                    @foreach ($datossub as $datossubs)
                        @php
                            $hoy1 = $fecha;
                        @endphp
                        @if (array_search($datossubs['sub'], $histo) != true)
                            @php
                                array_push($histo, $datossubs['sub']);
                                $item = strtr($datossubs['sub'], ' ', '_');
                                $wctpar = $datossubs['wrk'] ?? 'xxxx';
                                $namenA = strtr($datossubs['sub'], ' ', '_');
                            @endphp
                            <div class="flex items-start  border rounded border-solid ">
                                <div class=" w-48 bg-gray-50 border-r p-1">
                                    <div class="text-sm text-gray-500">Item</div>
                                    <div class="font-bold">{{ $datossubs['sub'] }}</div>
                                    <div class="text-sm text-gray-500">SNP: {{ $datossubs['Qty'] }}</div>
                                    <div class="text-sm text-gray-500"> WC: {{ $datossubs['wrk'] }}</div>
                                    {{-- <div class="text-sm text-gray-500"> Min balance: {{ $datossubs['minbal'] }}</div> --}}
                                    <div class="text-sm text-gray-500"> Contenedor:{{ $datossubs['typkt'] }}</div>
                                    <input type="checkbox" id="{{ 'Che/on/' . $namenA }}"
                                        name="{{ 'Che/on/' . $namenA }}" /> Aplica cambio <br />
                                    <div class="font-bold"> Nivel:--------{{ $datossubs['level'] }}<br></div>



                                </div>

                                <div class="flex flex-col items-center  border rounded space-y-2 p-4">
                                    <!-- Fecha -->
                                    <div class="font-bold">Dia </div>
                                    <!-- Contenedor horizontal para los bloques -->
                                    <div class="flex space-x-4">
                                        <!-- Bloque 1 -->
                                        <div class="w-40 text-center border rounded shadow p-1">
                                            <div class="font-bold">Turno</div>
                                            <div class="text-sm text-gray-500 border-red-400  ">Pronostico</div>

                                            <div class="text-sm text-gray-500">Cant requerida</div>
                                            <div class="text-sm text-gray-500">Cant necesaria</div>
                                            <div class="text-sm text-gray-500"> Plan</div>
                                            <div class="text-sm text-gray-500">Remanente</div>
                                            <div class="text-sm text-gray-500">Plan</div>
                                            <div class="text-sm text-gray-500">Firme"</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 overflow-x-auto">
                                    <div class="flex whitespace-nowrap">

                                        @php
                                            $coni = 0;
                                            $hoy1 = $fecha;
                                            $plan = $datossubs['plan'];
                                            $offset = $datossubs['offset'];

                                            $totalplan = 0;
                                            $totalKMRP = 0;
                                            $totalfir = 0;
                                            $totalkfp = 0;

                                            $forcast = $datossubs['forcast'];
                                            $totalplan = array_sum($forcast);
                                            $totalcarry = 0;
                                            $totalpreq = 0;
                                            $totaloqty = 0;
                                            $totalvalFH = 0;
                                            $CONTCARR = 0;
                                            $totaloplan = 0;
                                        @endphp

                                        @while ($coni < $dias)
                                            @php

                                                if (array_key_exists('For' . $hoy1 . 'D', $plan) == false) {
                                                    $valFDH = '0';
                                                } else {
                                                    $valFDH = $plan['For' . $hoy1 . 'D'];
                                                }

                                                if (array_key_exists('For' . $hoy1 . 'N', $plan) == false) {
                                                    $valFNH = '0';
                                                } else {
                                                    $valFNH = $plan['For' . $hoy1 . 'N'];
                                                }
                                                $var = 'R' . $hoy . 'D';
                                                $re = 0;
                                                $valRDH = 0;
                                                $valRNH = 0;

                                                if (array_key_exists('FMA' . $hoy1 . 'D', $forcast) == true) {
                                                    $valRDH = $valRDH + $forcast['FMA' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('kmr' . $hoy1 . 'D', $forcast) == true) {
                                                    $valRDH = $valRDH + $forcast['kmr' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('ecl' . $hoy1 . 'D', $forcast) == true) {
                                                    $valRDH = $valRDH + $forcast['ecl' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('kmr' . $hoy1 . 'N', $forcast) == true) {
                                                    $valRNH = $valRNH + $forcast['kmr' . $hoy1 . 'N'];
                                                }
                                                if (array_key_exists('ecl' . $hoy1 . 'N', $forcast) == true) {
                                                    $valRNH = $valRNH + $forcast['ecl' . $hoy1 . 'N'];
                                                }

                                                $totalKMRP = $totalKMRP + $valRDH + $valRNH;

                                                if (array_key_exists('P' . $hoy1 . 'D', $plan) == false) {
                                                    $valPDH = '0';
                                                } else {
                                                    $valPDH = $plan['P' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('P' . $hoy1 . 'N', $plan) == false) {
                                                    $valPNH = '0';
                                                } else {
                                                    $valPNH = $plan['P' . $hoy1 . 'N'];
                                                }
                                                $totalplan = $valPDH + $valPNH + $totalplan;

                                                if (array_key_exists('F' . $hoy1 . 'D', $plan) == false) {
                                                    $valFiDH = $valPDH;
                                                } else {
                                                    $valFiDH = $plan['F' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('F' . $hoy1 . 'N', $plan) == false) {
                                                    $valFiNH = $valPNH;
                                                } else {
                                                    $valFiNH = $plan['F' . $hoy1 . 'N'];
                                                }
                                                $totalfir = $totalfir + $valFiDH + $valFiNH;
                                                if (array_key_exists('S' . $hoy1 . 'D', $plan) == false) {
                                                    $valSDH = '-';
                                                } else {
                                                    $valSDH = $plan['S' . $hoy1 . 'D'];
                                                }

                                                if (array_key_exists('S' . $hoy1 . 'N', $plan) == false) {
                                                    $valSNH = '-';
                                                } else {
                                                    $valSNH = $plan['S' . $hoy1 . 'N'];
                                                }

                                                if (array_key_exists('KMRS' . $hoy1 . 'D', $forcast) == false) {
                                                    $valKMRsd = '-';
                                                } else {
                                                    $valKMRsd = $forcast['KMRS' . $hoy1 . 'D'];
                                                }

                                                if (array_key_exists('KMRS' . $hoy1 . 'N', $forcast) == false) {
                                                    $valMKMRsn = '-';
                                                } else {
                                                    $valMKMRsn = $forcast['KMRS' . $hoy1 . 'N'];
                                                }
                                                if (array_key_exists('kfp' . $hoy1 . 'D', $forcast) == false) {
                                                    $valkfpsd = '0';
                                                } else {
                                                    $valkfpsd = $forcast['kfp' . $hoy1 . 'D'];
                                                }

                                                if (array_key_exists('kfp' . $hoy1 . 'N', $forcast) == false) {
                                                    $valMkfpsn = '0';
                                                } else {
                                                    $valMkfpsn = $forcast['kfp' . $hoy1 . 'N'];
                                                }
                                                $totalkfp += $valkfpsd + $valMkfpsn;

                                                // OFFSET
                                                // dd($info,$offset,'opreq' . $hoy . 'D',array_key_exists('opreq' . $hoy . 'D', $offset));
                                                if (array_key_exists('opreq' . $hoy1 . 'D', $offset) == false) {
                                                    $prreqD = '0';
                                                } else {
                                                    $prreqD = $offset['opreq' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('oqty' . $hoy1 . 'D', $offset) == false) {
                                                    $oqtyD = '0';
                                                } else {
                                                    $oqtyD = $offset['oqty' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('oplan' . $hoy1 . 'D', $offset) == false) {
                                                    $oplanD = '0';
                                                } else {
                                                    $oplanD = $offset['oplan' . $hoy1 . 'D'];
                                                }
                                                if (array_key_exists('ocarry' . $hoy1 . 'D', $offset) == false) {
                                                    $ocarryD = '0';
                                                } else {
                                                    $ocarryD = $offset['ocarry' . $hoy1 . 'D'];
                                                }

                                                //-----------NOCHE
                                                if (array_key_exists('opreq' . $hoy1 . 'N', $offset) == false) {
                                                    $prreqN = '0';
                                                } else {
                                                    $prreqN = $offset['opreq' . $hoy1 . 'N'];
                                                }
                                                if (array_key_exists('oqty' . $hoy1 . 'N', $offset) == false) {
                                                    $oqtyN = '0';
                                                } else {
                                                    $oqtyN = $offset['oqty' . $hoy1 . 'N'];
                                                }
                                                if (array_key_exists('oplan' . $hoy1 . 'N', $offset) == false) {
                                                    $oplanN = '0';
                                                } else {
                                                    $oplanN = $offset['oplan' . $hoy1 . 'N'];
                                                }
                                                if (array_key_exists('ocarry' . $hoy1 . 'N', $offset) == false) {
                                                    $ocarryN = '0';
                                                } else {
                                                    $ocarryN = $offset['ocarry' . $hoy1 . 'N'];
                                                }
                                                if ($CONTCARR == 8) {
                                                    $CONTCARR = 1;
                                                    $totalcarry = 0;
                                                    $totalcarry = $ocarryD;
                                                } else {
                                                    $CONTCARR = $CONTCARR + 1;
                                                    if ($CONTCARR <= 7) {
                                                        if ($ocarryD != 0) {
                                                            $totalcarry = $ocarryD;
                                                            if ($ocarryN != 0) {
                                                                $totalcarry = $ocarryN;
                                                            }
                                                        } else {
                                                            if ($ocarryN != 0) {
                                                                $totalcarry = $ocarryN;
                                                            }
                                                        }
                                                    }
                                                    $totalpreq += $prreqD + $prreqN;
                                                    $totaloqty += $oqtyN + $oqtyD;
                                                    $totalvalFH += $valFNH + $valFDH;
                                                    $totaloplan += $oplanD + $oplanN;
                                                }
                                            @endphp
                                            @if ($CONTCARR == 8)
                                                <div class="flex flex-col items-center space-y-2 p-4">

                                                    @php
                                                        $namenA = strtr($datossubs['sub'], ' ', '_');
                                                        $inD = $namenA . '/' . $hoy1 . '/D/' . $datossubs['wrk'];
                                                        $inN = $namenA . '/' . $hoy1 . '/N/' . $datossubs['wrk'];
                                                        $WRCj = $datossubs['wrk'];
                                                        $namep = $datossubs['sub'];
                                                    @endphp

                                                    <!-- Fecha -->

                                                    <div class="font-bold">Total
                                                    </div>
                                                    <!-- Contenedor horizontal para los bloques -->
                                                    <div class="flex space-x-4 border border-red-700">
                                                        <!-- Bloque 1 -->
                                                        <div class="w-24 text-center border rounded shadow">
                                                            <div class="font-bold">-</div>
                                                            <div class="text-sm ">{{ $totalvalFH }}</div>
                                                            {{-- <div class="text-sm text-gray-500">{{ $valeclD }}</div>
                                                        <div class="text-sm ">{{ $valkfpsd }}</div> --}}

                                                            <div class="text-sm text-gray-500">{{ $totalpreq }}
                                                            </div>
                                                            <div class="text-sm ">{{ $totaloqty }}</div>
                                                            <div class="text-sm text-gray-500">{{ $totaloplan }}
                                                            </div>
                                                            <div class="text-sm ">{{ $totalcarry }}</div>
                                                            {{-- <div class="text-sm text-gray-500">{{ $valPDH }}</div> --}}
                                                            <div class="text-sm flex items-center justify-center">
                                                                <input type="number" min="0"
                                                                    class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                                                    disabled />
                                                            </div>
                                                            <div class="text-sm text-gray-500">{{ $valSDH }}
                                                            </div>

                                                        </div>
                                                        <!-- Bloque 2 -->

                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex flex-col  border rounded items-center space-y-2 p-4">

                                                    @php

                                                        $inD = $namenA . '/' . $hoy1 . '/D/' . $datossubs['wrk'];
                                                        $inN = $namenA . '/' . $hoy1 . '/N/' . $datossubs['wrk'];
                                                        $WRCj = $datossubs['wrk'];
                                                        $namep = $datossubs['sub'];
                                                    @endphp

                                                    <!-- Fecha -->

                                                    <div class="font-bold ">{{ date('d', strtotime($hoy1)) }}
                                                    </div>
                                                    <!-- Contenedor horizontal para los bloques -->
                                                    <div class="flex space-x-4">
                                                        <!-- Bloque 1 -->
                                                        <div class="w-24 text-center border rounded shadow">
                                                            <div class="font-bold">D</div>
                                                            <div class="text-sm ">{{ $valRDH }}</div>
                                                            {{-- <div class="text-sm text-gray-500">{{ $valeclD }}</div>
                                                            <div class="text-sm ">{{ $valkfpsd }}</div> --}}

                                                            <div class="text-sm text-gray-500">{{ $prreqD }}
                                                            </div>
                                                            <div class="text-sm ">{{ $oqtyD }}</div>
                                                            <div class="text-sm text-gray-500">{{ $oplanD }}
                                                            </div>
                                                            <div class="text-sm ">{{ $ocarryD }}</div>
                                                            {{-- <div class="text-sm text-gray-500">{{ $valPDH }}</div> --}}
                                                            <div class="text-sm flex items-center justify-center">
                                                                <input id="{{ $inD }}"
                                                                    name="{{ $inD }}"
                                                                    value="{{ $oplanD }}"
                                                                    onchange="myFunction('<?php echo $diasjava; ?>', '<?php echo $namenA; ?>','<?php echo $wctpar; ?>',this.id)"
                                                                    type="number" min="0"
                                                                    class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                            </div>
                                                            <div class="text-sm text-gray-500">{{ $valSDH }}
                                                            </div>

                                                        </div>
                                                        <!-- Bloque 2 -->
                                                        <div class="w-24 text-center border rounded shadow ">
                                                            <div class="font-bold">N</div>
                                                            <div class="text-sm border-y-gray-900">
                                                                {{ $valRNH }}</div>
                                                            <div class="text-sm text-gray-500">{{ $prreqN }}
                                                            </div>
                                                            <div class="text-sm border-y-gray-900">
                                                                {{ $oqtyN }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">{{ $oplanN }}
                                                            </div>
                                                            <div class="text-sm border-y-gray-900">
                                                                {{ $ocarryN }}
                                                            </div>
                                                            {{-- <div class="text-sm text-gray-500">{{ $valPNH }}</div> --}}
                                                            <div class="text-sm  "> <input id={{ $inN }}
                                                                    name={{ $inN }} value={{ $oplanN }}
                                                                    onchange="myFunction('<?php echo $diasjava; ?>', '<?php echo $namenA; ?>','<?php echo $wctpar; ?>',this.id)"
                                                                    type="number" min="0"
                                                                    class="block w-20 text-xs dark:border-green-600 dark:bg-green-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                                                            </div>
                                                            <div class="text-sm text-gray-500">{{ $valSNH }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php
                                                    $hoy1 = date('Ymd', strtotime($hoy1 . '+1 day'));
                                                    $coni++;
                                                @endphp
                                            @endif
                                        @endwhile
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
    </form>


    <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
        <form method="post" action="{{ route('planeacion.siguiente') }}">
            <div class="flex flex-row gap-x-4 justify-end items-center p-0 rounded-lg">
                @csrf

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

            console.log('Che/on/' + parte);
            document.getElementById('Che/on/' + parte).checked = true;
            val1 = parseInt(document.getElementById(parte + '/' + arr[1] + '/D/' + wc).value);
            val2 = parseInt(document.getElementById(parte + '/' + arr[1] + '/N/' + wc).value);
            val3 = parseInt(document.getElementById(parte + '/' + arr[2] + '/D/' + wc).value);
            val4 = parseInt(document.getElementById(parte + '/' + arr[2] + '/N/' + wc).value);
            val5 = parseInt(document.getElementById(parte + '/' + arr[3] + '/D/' + wc).value);
            val6 = parseInt(document.getElementById(parte + '/' + arr[3] + '/N/' + wc).value);
            val7 = parseInt(document.getElementById(parte + '/' + arr[4] + '/D/' + wc).value);
            val8 = parseInt(document.getElementById(parte + '/' + arr[4] + '/N/' + wc).value);
            val9 = parseInt(document.getElementById(parte + '/' + arr[5] + '/D/' + wc).value);
            val10 = parseInt(document.getElementById(parte + '/' + arr[5] + '/N/' + wc).value);
            val11 = parseInt(document.getElementById(parte + '/' + arr[0] + '/D/' + wc).value);
            console.log(parte + '/' + arr[4] + '/D/' + wc);
            console.log(arr[0], arr[1], arr[2], arr[3], arr[4], arr[5]);
            valtotal = val1 + val2 + val3 + val4 + val5 + val6 + val7 + val8 + val9 + val10 + val11;
            document.getElementById('totalFirykm' + parte).value = valtotal;
            console.log(valtotal, document.getElementById('totalFirykm' + parte).value);




        }
    </script>

</x-app-layout>
