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

            <th rowspan="2"></th>
            <th rowspan="2">
                Parte <br> Total
            </th>
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
                $contdias = 0;
                
            @endphp
            <tr>
                <td rowspan="3">
                    {{ $info['parte'] }}
                </td>

                <td>
                    Forecast MMVO
                </td>
                <td>
                    {{ $info['total'] }}
                </td>
                @while ($contdias < $dias)
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
                        $contdias++;
                    @endphp
                @endwhile

            </tr>
            <tr>
                <td>
                    Firme MMVO
                </td>
                <td>
                    {{ $info['tPlan'] }}
                </td>
                @while ($contdias < $dias)
                    @php
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
            <tr>
                <td>
                    Firme YKM
                </td>
                <td>
                    {{ $info['tPlan'] }}
                </td>
                @while ($contdias < $dias)
                    @php
                        
                        if (array_key_exists('F' . $hoy . 'D', $info) == false) {
                            $valPD = '-';
                        } else {
                            $valPD = $info['F' . $hoy . 'D'];
                        }
                        if (array_key_exists('F' . $hoy . 'N', $info) == false) {
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
