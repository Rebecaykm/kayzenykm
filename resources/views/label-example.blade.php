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
            padding: 0 8px;
            margin: 0 2px;
            word-wrap: break-word;
            border-style: solid;
            border-width: thin;
            border-radius: 5px;
        }

        .left-align {
            text-align: left;
        }

        .center-align {
            text-align: center;
        }

        .vertical-center {
            display: flex;
            align-items: center;
        }

        .no-border {
            border: none;
        }

        .text-3xs {
            font-size: 0.375rem;
            line-height: 0.25rem;
        }

        .text-25xs {
            font-size: 0.5rem;
            line-height: 0.5rem;
        }

        .text-2xs {
            font-size: 0.625rem;
            line-height: 0.75rem;
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .text-base {
            font-size: 1rem;
            line-height: 1.5rem;
        }

        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        .text-25xl {
            font-size: 1.625rem;
            line-height: 1;
        }

        .text-3xl {
            font-size: 3rem;
            line-height: 1;
        }

        .font-extralight {
            font-weight: 200;
        }

        .font-light {
            font-weight: 300;
        }

        .font-normal {
            font-weight: 400;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-extrabold {
            font-weight: 800;
        }

        .font-black {
            font-weight: 900;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <table class="center-align">
        @foreach ($dataArrayWithQr as $data)
        <tr class="page-break">
            <td>
                <span class="text-3xs font-light"> {{ __('Departamento') }}: </span>
                <span class="text-xs font-semibold center-align" style="display: inline-block; width: 100%;"> {{ $data['departament'] }} </span>
            </td>
            <td>
                <span class="text-3xs font-light"> {{ __('Estación') }}: </span>
                <span class="text-xs font-semibold center-align" style="display: inline-block; width: 100%;"> {{ $data['workcenterName'] }} </span>
            </td>
            <td>
                <span class="text-3xs font-light"> {{ __('Proyecto') }}: </span>
                <span class="text-xs font-semibold center-align" style="display: inline-block; width: 100%;">
                    @foreach ($data['projects'] as $project)
                    {{ $project['model'] ?? '' }}
                    @endforeach
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="left-align">
                <span class="text-3xs font-light"> {{ __('No. Parte') }}: </span>
                <span class="text-3xl font-bold center-align" style="display: inline-block; width: 100%;"> {{ $data['partNumber'] }} </span>
            </td>
        </tr>
        <tr>

            <td colspan="2">
                <span class="text-3xs font-light"> {{ __('Fecha de Producción') }}: </span>
                <span class="text-xs font-semibold center-align" style="display: inline-block; width: 100%;">
                    {{ \Carbon\Carbon::parse($data['date'])->format('d/m/Y') }} &nbsp;&nbsp; {{ $data['shift'] }}
                </span>
            </td>
            <td>
                <span class="text-3xs font-light">{{ __('Secuencia') }}:</span>
                <span class="text-xs font-semibold center-align" style="display: inline-block; width: 100%;">{{ $data['sequence'] }} </span>
            </td>
        </tr>
        <tr class="left-align">
            <td>
                <span class="text-3xs font-light"> {{ __('Contenedor') }}: </span>
                <span class="{{ strlen($data['container']) > 8 ? 'text-lg' : 'text-25xl' }} font-semibold center-align" style="display: inline-block; width: 100%;"> {{ $data['container'] }} </span>
            </td>
            <td>
                <span class="text-3xs font-light"> {{ __('S N P') }}:</span>
                <span class="text-25xl font-semibold center-align" style="display: inline-block; width: 100%;"> {{ intval($data['snp']) }} </span>
            </td>
            <td>
                <span class="text-3xs font-light">{{ __('Cantidad Produccido') }}:</span>
                <span class="text-25xl font-semibold center-align" style="display: inline-block; width: 100%;">{{ intval($data['quantity']) }} </span>
            </td>

        </tr>
        <tr>
            <td class="text-xs  font-base center-align no-border">
                {{ __('IDENTIFICATION CARD') }}
            </td>
            <td class="text-xs font-base center-align no-border">
                {{ __('Y-TEC KEYLEX MÉXICO') }}
            </td>
            <td class="center-align no-border" style="max-width: 100%; max-height: 100%;" rowspan="2">
                <img src="data:image/svg+xml;base64, {!! base64_encode($data['qrCode']) !!}" width="50" height="50">
            </td>
        </tr>
        <tr>
            <td class="text-2xs font-base center-align no-border">
                {{ $data['a'] }}
            </td>
            <td class="center-align no-border">
                <span class="text-25xs font-light" style="display: block;"> {{ __('FECHA DE IMPRESIÓN') }} </span>
                <span class="text-25xs font-light" style="display: block;"> {{ date("d-m-Y H:i:s") }} </span>
            </td>
        </tr>
        @endforeach
    </table>

</body>

</html>
