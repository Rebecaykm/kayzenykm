<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: 6in 3in;
                /* size: 4in 3in; */
                margin: 2px;
            }
        }

        @page {
            /* size: 4in 3in; */
            size: 6in 3in;
            margin: 2px;
        }

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
    </style>
</head>

<body>

    <table>
        <tr>
            <td colspan="1">
                <span class="small-text bold">Departamento:</span>
                <br>
                <span class="medium-text"> {{ $departament }}</span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Proyeecto:</span>
                <br>
                <span class="medium-text">
                    @foreach ($projects as $project)
                    {{ $project['model'] ?? '' }}
                    @endforeach
                </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Clase:</span>
                <br>
                <span class="medium-text">{{ $class }}</span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Estación:</span>
                <br>
                <span class="medium-text">{{ $workcenterName }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="small-text bold">No. Parte</span>
                <br>
                <span class="medium-text">{{ $partNumber }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Fecha</span>
                <br>
                <span class="medium-text">{{ $date }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Turno</span>
                <br>
                <span class="medium-text">{{ $shift }} </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="small-text bold">Secuencia</span>
                <br>
                <span class="medium-text">{{ $sequence }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Contenedor</span>
                <br>
                <span class="medium-text"> {{ $container }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Cantidad</span>
                <br>
                <span class="medium-text">{{ $quantity }} </span>
            </td>
        </tr>
        <tr>
            <td class="large-text text-center bold no-border" colspan="2">
                IDENTIFICATION CARD
                <br>
                Y-TEC KEYLEX MÉXICO
            </td>
            <td class="no-border text-center" colspan="4" style="max-width: 80%; max-height: 80%;">
                <img src="data:image/png;base64, {!! base64_encode($qrCode) !!}" width="75" height="75">
            </td>
        </tr>
        <tr>
            <td class="large-text text-center bold no-border" colspan="2">
                {{ $a }}
            </td>
            <td class="small-text text-center bold no-border" colspan="2">
                {{ date("Y-m-d H:i:s") }}
            </td>
        </tr>
    </table>
</body>

</html>
