<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


@php
    $res = $general['res'];
    $dias = $general['dias'];
    $fecha = $general['fecha'];
@endphp
<table class="w-full whitespace-no-wrap ">

    <thead>
        <tr>
            <th  rowspan="2">No Parte Final </th>
            <th  rowspan="2">
                Parte <br> componente
            </th>
            <th rowspan="2">
                Parte <br> Finales
            </th>
            <th  rowspan="2"></th>
            <th  rowspan="2">
                Parte <br> Total
            </th>
            @php
                $hoy = $fecha;
                $totalD = 0;
                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
            @endphp
            @while ($hoy != $fin)
                <th aling="center"  colspan="2">
                    <div class='W-full'>
                        {{ date('Ymd', strtotime($hoy)) }}
                    </div>
                </th>
                @php
                    $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                    $totalD = $totalD + 1;
                @endphp
            @endwhile
        </tr>
        <tr>
            @php
                $hoy = $fecha;
                $totalD = 0;
                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
            @endphp

            @while ($hoy != $fin)
                <th>

                    Dia
                </th>
                <th>
                    Noche

                </th>
                @php
                    $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                    $totalD = $totalD + 1;
                @endphp
            @endwhile
        </tr>

    </thead>
    <tbody >

        @foreach ($res as $info1)
            @php

                $info = $info1['padre'];
                $fore= $info['fore'];
                $contdias = 0;

            @endphp
            <tr>
                <td rowspan="3">
                    {{ $info['parte'] }}
                </td>
                <td rowspan="3">
                </td>
                <td rowspan="3">
                </td>
                <td>
                    Forecast
                </td>
                <td>

                </td>
                @while ($contdias < $dias)
                    @php
                        if (array_key_exists('Fore' . $hoy . 'D', $info) == false) {
                            $valFD = '-';
                        } else {
                            $valFD = $info['Fore' . $hoy . 'D'];
                        }
                        if (array_key_exists('Fore' . $hoy . 'N', $info) == false) {
                            $valFN = '-';
                        } else {
                            $valFN = $info['Fore' . $hoy . 'N'];
                        }
                    @endphp
                    <td>
                        {{ $valFD }}
                    </td>
                    <td>
                        {{ $valFN }}
                    </td>
                    @php
                        $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                        $contdias++;
                    @endphp
                @endwhile

            </tr>

            <tr >
                <td>
                    Firme
                </td>
                <td>

                </td>
                @while ($contdias < $dias)
                    @php
                        if (array_key_exists('F' . $hoy . 'D', $fore) == false) {
                            $valPD = '-';
                        } else {
                            $valPD = $info['F' . $hoy . 'D'];
                        }
                        if (array_key_exists('F' . $hoy . 'N', $fore) == false) {
                            $valPN = '-';
                        } else {
                            $valPN = $info['F' . $hoy . 'N'];
                        }
                    @endphp
                    <td>
                        {{ $valPD }}
                    </td>
                    <td>
                        {{ $valPN }}
                    </td>
                    @php
                        $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                        $contdias++;
                    @endphp
                @endwhile

                @php
                    $hoy = $fecha;
                    $contdias = 0;
                @endphp
            </tr>
       @php

                $datossub = $info1['hijos'];

            @endphp
            @foreach ($datossub as $datossubs)
                @php
                    $hoy = $fecha;
                    $forcast = $datossubs['forcast'];
                    $totalplan = array_sum($forcast);

                @endphp
                <tr>
                    <th rowspan="3">
                    </th>
                    <th rowspan="3">
                        {{ $datossubs['sub'] }}<br>
                        @php
                            $wctpar = 'wrk' . $datossubs['wrk'];
                        @endphp
                        {{-- SNP: {{ $datossubs['Qty'] }} <br>
                        Wrkcente: {{ $datossubs['wrk'] }}<br>
                        Min balance: {{ $datossubs['minbal'] }} --}}

                    </th>
                    <th rowspan="3">
                        {{ $datossubs['padres'] }}
                    </th>
                    <th>
                        Requeriment (Forecast)
                    </th>
                    <th>
                    </th>
                    @php
                        $conti = 0;
                        $hoy = $fecha;
                        $plan = $datossubs['plan'];

                    @endphp
                    @while ($conti <= $dias)
                        @php
                            if (array_key_exists('For' . $hoy . 'D', $info) == false) {
                                $valFD = '-';
                            } else {
                                $valFD = $info['For' . $hoy . 'D'];
                            }
                            if (array_key_exists('For' . $hoy . 'N', $info) == false) {
                                $valFN = '-';
                            } else {
                                $valFN = $info['For' . $hoy . 'N'];
                            }
                        @endphp
                        <td>
                            {{ $valFD }}
                        </td>
                        <td>
                            {{ $valFN }}
                        </td>
                        @php
                            $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                            $conti++;
                        @endphp
                    @endwhile




                </tr>

                <tr>
                    <th>
                        Plan
                    </th>
                    <th>
                        {{ $datossubs['Tplan'] }}
                    </th>
                    @php
                    $coni = 0;
                    $hoy1 = $fecha;
                @endphp
                @while ($coni <= $dias)
                    @php

                        if (array_key_exists('P' . $hoy1 . 'D', $plan) == false) {
                            $valFiDh = 0;
                        } else {
                            $valFiDh = $plan['P' . $hoy1 . 'D'];
                        }
                        if (array_key_exists('P' . $hoy1 . 'N', $plan) == false) {
                            $valFiNH = 0;
                        } else {
                            $valFiNH = $plan['P' . $hoy1 . 'N'];
                        }
                    @endphp
                    <th>
                        {{ $valFiDh }}
                    </th>
                    <th>
                        {{ $valFiNH  }}

                    </th>
                    @php
                        $hoy1 = date('Ymd', strtotime($hoy1 . '+1 day'));
                        $coni++;
                    @endphp
                @endwhile


                </tr>
                <tr>
                    <th>
                        Firme
                    </th>
                    <th>

                    </th>
                    @php
                        $coni = 0;
                        $hoy1 = $fecha;
                    @endphp
                    @while ($coni <= $dias)
                        @php

                            if (array_key_exists('F' . $hoy1 . 'D', $plan) == false) {
                                $valFiDh = 0;
                            } else {
                                $valFiDh = $plan['F' . $hoy1 . 'D'];
                            }
                            if (array_key_exists('F' . $hoy1 . 'N', $plan) == false) {
                                $valFiNH = 0;
                            } else {
                                $valFiNH = $plan['F' . $hoy1 . 'N'];
                            }
                        @endphp
                        <th>
                            {{ $valFiDh }}
                        </th>
                        <th>
                            {{ $valFiNH  }}

                        </th>
                        @php
                            $hoy1 = date('Ymd', strtotime($hoy1 . '+1 day'));
                            $coni++;
                        @endphp
                    @endwhile




                </tr>

            @endforeach

        @endforeach

    </tbody>
</table>
