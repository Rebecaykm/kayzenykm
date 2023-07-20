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
          

        @endforeach
       
        
    </tbody>
</table>
