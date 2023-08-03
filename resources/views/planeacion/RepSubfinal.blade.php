<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


@php
    $res = $general['res'];
    $dias = $general['dias'];
    $fecha = $general['fecha'];
@endphp
<table class="w-full whitespace-no-wrap ">

    <thead>
        <tr>
            <th rowspan="2">No Parte Final </th>
            <th rowspan="2">
                Parte <br> componente
            </th>
            <th rowspan="2">
                Workcenter
            </th>
            <th rowspan="2"></th>
            @php
                $hoy = $fecha;
                $totalD = 0;
                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
            @endphp
            @while ($hoy != $fin)
                <th aling="center" colspan="2">
                    <div class='W-full'>
                        {{ date('Ymd', strtotime($hoy)) }}
                    </div>
                </th>
                @php
                    $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                    $totalD = $totalD + 1;
                @endphp
            @endwhile
            <th rowspan="2">
                Total
            </th>
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
    <tbody>


        @foreach ($res as $info1)
            @php
                $info = $info1['padre'];
                $infohijos = $info1['hijos'];
                $fore = $info['fore'];
                $contdias = 0;
                $totalp = 0;
                $hoy = $fecha;
            @endphp
            <tr>
                <td>
                    {{ $info['parte'] }}
                </td>
                <td>
                </td>
                <td>
                </td>

                <td>
                    Firme
                </td>


                @while ($contdias < $dias)

                    @php
                        if (array_key_exists('F' . $hoy . 'D', $fore) == false) {
                            $valPD = 0;
                        } else {
                            $valPD = $fore['F' . $hoy . 'D'];
                        }
                        if (array_key_exists('F' . $hoy . 'N', $fore) == false) {
                            $valPN = 0;
                        } else {
                            $valPN = $fore['F' . $hoy . 'N'];
                        }
                        $totalp += $valPD + $valPN;
                //         if ($info['parte']  =="DGH928B0XD                         ") {
                //     dd($info1['padre'],$hoy ,$contdias, $fore);
                // }
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
                <td>
                    {{ $totalp }}
                </td>
                @php
                    $hoy = $fecha;
                    $contdias = 0;
                    $totalp = 0;
                @endphp
            </tr>
            @foreach ($infohijos as $hijo)
                @php
                    if (array_key_exists('Forehijo', $hijo) == false) {
                        $hijop = [];
                    }
                    $forehijo = $hijo['Forehijo'] ?? $hijop;
                    $totalh = 0;
                @endphp
                <tr>
                    <td>

                    </td>
                    {{ $hijo['parte'] }}
                    <td>
                    </td>
                    <td>
                        {{ $hijo['WKC'] }}
                    </td>

                    <td>
                        Firme
                    </td>

                    @while ($contdias < $dias)
                        @php

                            if (array_key_exists('F' . $hoy . 'D', $forehijo) == false) {
                                $valPD = 0;
                            } else {
                                $valPD = $forehijo['F' . $hoy . 'D'];
                            }
                            if (array_key_exists('F' . $hoy . 'N', $forehijo) == false) {
                                $valPN = 0;
                            } else {
                                $valPN = $forehijo['F' . $hoy . 'N'];
                            }
                            $totalh += $valPD + $valPN;
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
                    <td>
                        {{ $totalh }}
                    </td>
                    @php
                        $hoy = $fecha;
                        $contdias = 0;
                        $totalh = 0;
                    @endphp
                </tr>
            @endforeach
        @endforeach


    </tbody>
</table>
