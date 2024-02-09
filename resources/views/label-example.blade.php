<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:100,400,400i,600,800">
    <style>
        @media print {
            @page {
                size: 178mm 76mm;
                margin: 0;
                /* padding: 8px; */
            }
        }

        @page {
            size: 178mm 76mm;
            margin: 0;
        }

        body {
            margin: 0;
            font-family: Arial, 'Roboto', sans-serif;
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
            font-size: 12px;
        }

        .large-text {
            font-size: 16px;
        }

        .xl-text {
            font-size: 20px;
        }

        .xxl-text {
            font-size: 40px;
            font-weight: 800;
        }

        .text-center {
            text-align: center;
        }

        .no-border {
            border: none;
        }

        .bold {
            font-weight: 600;
        }

        .page-break {
            page-break-before: always;
        }

        .vertical {
            transform: rotate(270deg);
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
                <span class="medium-text"> {{ $data['departament'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Estación') }}:</span>
                <br>
                <span class="medium-text">{{ $data['workcenterName'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Proyecto') }}:</span>
                <span class="medium-text">
                    @foreach ($data['projects'] as $project)
                    {{ $project['model'] ?? '' }}
                    @endforeach
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="small-text">{{ __('No. Parte') }}:</span>
                <br>
                <span class="xxl-text bold">{{ $data['partNumber'] }} </span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="small-text">{{ __('Fecha Plan') }}:</span>
                <br>
                <span class="medium-text">{{ \Carbon\Carbon::parse($data['date'])->format('d/m/Y') }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Turno Plan') }}:</span>
                <br>
                <span class="medium-text">{{ $data['shift'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Contenedor') }}:</span>
                <br>
                <span class="medium-text"> {{ $data['container'] }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="small-text">{{ __('Cantidad SNP') }}:</span>
                <br>
                <span class="medium-text">{{ $data['snp'] }}</span>
            </td>
            <td>
                <span class="small-text">{{ __('Cantidad Produccido') }}:</span>
                <br>
                <span class="medium-text">{{ $data['quantity'] }} </span>
            </td>
            <td>
                <span class="small-text">{{ __('Secuencia') }}:</span>
                <br>
                <span class="medium-text">{{ $data['sequence'] }} </span>
            </td>
        </tr>
        <tr>
            <td class="medium-text text-center bold no-border">
                {{ __('IDENTIFICATION CARD') }}
                <br>
                {{ $data['a'] }}
            </td>
            <td class="medium-text text-center bold no-border">
                {{ __('Y-TEC KEYLEX MÉXICO') }}
                <br>
                <span class="small-text">
                    {{ __('FECHA DE IMPRESIÓN') }}
                    <br>
                    {{ date("d-m-Y H:i:s") }}
                </span>
            </td>
            <td class="no-border text-center" rowspan="2">
                <img src="data:image/svg+xml;base64, {!! base64_encode($data['qrCode']) !!}" width="40" height="40">
            </td>
        </tr>
        <br>
        @endforeach
    </table>

</body>

</html>
