<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: 152mm 76mm;
                margin: 0;
                /* padding: 8px; */
            }
        }

        @page {
            size: 152mm 76mm;
            margin: 0;
        }

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: separate;
            margin: 0;
            /* border-spacing: 6px; */
        }

        td {
            border: 1px solid black;
            padding: 4px;
            /* max-width: 25%; */
            word-wrap: break-word;
        }

        .small-text {
            font-size: 6px;
        }

        .medium-text {
            font-size: 14px;
        }

        .large-text {
            font-size: 18px;
        }

        .xl-text {
            font-size: 22px;
        }

        .xxl-text {
            font-size: 36px;
            font-weight: 800;
        }

        .text-center {
            text-align: center;
        }

        .no-border {
            border: none;
        }

        .semi-bold {
            font-weight: 600;
        }

        .bold {
            font-weight: 800;
        }

        .page-break {
            page-break-before: always;
        }

        .vertical {
            writing-mode: vertical-lr;
            text-orientation: upright;
        }
    </style>
</head>

<body>

    <table>
        @foreach ($dataArrayWithQr as $data)
        <tr class="page-break">
            <td>
                <span class="small-text">{{ __('Departamento') }}:</span>
                <br>
                <span class="medium-text semi-bold"> {{ $data['departament'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Estación') }}:</span>
                <br>
                <span class="medium-text semi-bold">{{ $data['workcenterName'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Proyecto') }}:</span>
                <br>
                <span class="medium-text semi-bold">
                    @foreach ($data['projects'] as $project)
                    {{ $project['model'] ?? '' }}
                    @endforeach
                </span>
            </td>
            <!-- <td rowspan="2" class="small-text bold vertical no-border">
                {{ __('IDENTIFICATION CARD') }}
                <br>
                {{ __('Y-TEC KEYLEX MÉXICO') }}
                <br>

            </td> -->
        </tr>
        <tr>
            <td colspan="3">
                <span class="small-text">{{ __('No. Parte') }}:</span>
                <br>
                <span class="xxl-text semi-bold">{{ $data['partNumber'] }} </span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="small-text">{{ __('Fecha Plan') }}:</span>
                <br>
                <span class="medium-text semi-bold">{{ \Carbon\Carbon::parse($data['date'])->format('d/m/Y') }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Turno Plan') }}:</span>
                <br>
                <span class="medium-text semi-bold">{{ $data['shift'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Contenedor') }}:</span>
                <br>
                <span class="medium-text semi-bold"> {{ $data['container'] }}</span>
            </td>
            <!-- <td rowspan="2" class="no-border">
                <img src="data:image/svg+xml;base64, {!! base64_encode($data['qrCode']) !!}" width="60" height="60">
            </td> -->
        </tr>
        <tr>
            <td>
                <span class="small-text">{{ __('Cantidad SNP') }}:</span>
                <br>
                <span class="medium-text semi-bold">{{ $data['snp'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Cantidad Produccido') }}:</span>
                <br>
                <span class="medium-text semi-bold">{{ $data['quantity'] }} </span>
            </td>
            <td>
                <span class="small-text">{{ __('Secuencia') }}:</span>
                <br>
                <span class="medium-text semi-bold">{{ $data['sequence'] }} </span>
            </td>
        </tr>
        <tr>
            <td class="medium-text text-center bold no-border">
                {{ __('IDENTIFICATION CARD') }}
            </td>
            <td class="medium-text text-center bold no-border">
                {{ __('Y-TEC KEYLEX MÉXICO') }}
            </td>
            <td class="no-border text-center" style="max-width: 80%; max-height: 80%;" rowspan="2">
                <img src="data:image/svg+xml;base64, {!! base64_encode($data['qrCode']) !!}" width="40" height="40">
            </td>
        </tr>
        <tr>
            <td class="medium-text text-center bold no-border">
                {{ $data['a'] }}
            </td>
            <td class="small-text text-center bold no-border">
                {{ __('FECHA DE IMPRESIÓN') }}
                <br>
                {{ date("d-m-Y H:i:s") }}
            </td>
        </tr>
        @endforeach
    </table>

</body>

</html>
