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
            margin: 4px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            /* border-spacing: 6px; */
            font-family: 'Roboto', sans-serif;
        }

        td {
            border: 1px solid black;
            padding: 5px;
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
            <td colspan="2">
                <span class="small-text bold">Departamento</span>
                <br>
                <span class="large-text"> {{ $departament }}</span>
            </td>
            <td colspan="2">
                <span class="small-text bold">Estación</span>
                <br>
                <span class="large-text"> {{ $workcenterName }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="small-text bold">No. Parte</span>
                <br>
                <span class="large-text">{{ $partNumber }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Fecha</span>
                <br>
                <span class="large-text">{{ $date }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Turno</span>
                <br>
                <span class="large-text">{{ $shift }} </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="small-text bold">Secuencia</span>
                <br>
                <span class="large-text">{{ $sequence }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Contenedor</span>
                <br>
                <span class="large-text"> {{ $container }} </span>
            </td>
            <td colspan="1">
                <span class="small-text bold">Cantidad</span>
                <br>
                <span class="large-text">{{ $quantity }} </span>
            </td>
        </tr>
        <tr>
            <td class="text-center bold no-border" colspan="2">
                IDENTIFICATION CARD
                <br>
                Y-TEC KEYLEX MÉXICO
            </td>
            <td class="no-border text-center" colspan="4" style="max-width: 100%; max-height: 100%;">
                <img src="data:image/png;base64, {!! base64_encode($qrCode) !!}" width="75" height="75">
            </td>
        </tr>
    </table>

</body>

</html>
