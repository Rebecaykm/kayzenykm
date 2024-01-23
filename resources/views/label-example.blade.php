<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: 152mm 76mm;
                margin: 8px;
                padding: 8px;
            }
        }

        /* @page {
            size: 120mm 160mm;
            margin: 8px;
        } */

        body {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: separate;
            /* border-spacing: 6px; */
            font-family: 'Roboto', sans-serif;
        }

        td {
            border: 1px solid black;
            padding: 4px;
            font-size: 12px;
            font-family: 'Roboto', sans-serif;
            max-width: 25%;
            word-wrap: break-word;
        }

        .small-text {
            font-size: 6px;
            font-family: 'Roboto', sans-serif;
        }

        .medium-text {
            font-size: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .large-text {
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
        }

        .xl-text {
            font-size: 18px;
            font-family: 'Roboto', sans-serif;
        }

        .text-center {
            text-align: center;
        }

        .no-border {
            border: none;
        }

        .bold {
            font-weight: bold;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <table>
        @foreach ($dataArrayWithQr as $data)
        <tr class="page-break">
            <td colspan="1">
                <span class="small-text bold">Departamento:</span>
                <br>
                <span class="medium-text"> {{ $data['departament'] }}</span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Proyecto:</span>
                <br>
                <span class="medium-text">
                    @foreach ($data['projects'] as $project)
                    {{ $project['model'] ?? '' }}
                    @endforeach
                </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Clase:</span>
                <br>
                <span class="medium-text">{{ $data['class'] }}</span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Estación:</span>
                <br>
                <span class="medium-text">{{ $data['workcenterName'] }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="small-text bold">No. Parte</span>
                <br>
                <span class="medium-text">{{ $data['partNumber'] }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Fecha</span>
                <br>
                <span class="medium-text">{{ $data['date'] }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Turno</span>
                <br>
                <span class="medium-text">{{ $data['shift'] }} </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="small-text bold">Secuencia</span>
                <br>
                <span class="medium-text">{{ $data['sequence'] }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Contenedor</span>
                <br>
                <span class="medium-text"> {{ $data['container'] }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Cantidad</span>
                <br>
                <span class="medium-text">{{ $data['quantity'] }} </span>
            </td>
        </tr>
        <tr>
            <td class="large-text text-center bold no-border" colspan="2">
                IDENTIFICATION CARD
                <br>
                Y-TEC KEYLEX MÉXICO
            </td>
            <td class="no-border text-center" colspan="4" style="max-width: 80%; max-height: 80%;">
                <img src="data:image/svg+xml;base64, {!! base64_encode($data['qrCode']) !!}" width="75" height="75">
            </td>
        </tr>
        <tr>
            <td class="large-text text-center bold no-border" colspan="2">
                {{ $data['a'] }}
            </td>
            <td class="small-text text-center bold no-border" colspan="2">
                {{ date("Y-m-d H:i:s") }}
            </td>
        </tr>
        @endforeach
    </table>

</body>

</html>
