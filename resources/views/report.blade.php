<!DOCTYPE html>
<html>

<head>
    <title>Reporte PDF</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            font-size: 8px;
            /* Tamaño de letra para el contenido */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            /* Espacio entre encabezado y tabla */
        }

        th,
        td {
            border: 1px solid black;
            padding: 2px;
            /* Espaciado vertical de 2px */
            text-align: left;
        }

        th {
            background-color: #e2e8f0;
            font-weight: bold;
            /* Encabezados en negrita */
            font-size: 10px;
            /* Tamaño de letra para los encabezados */
        }

        .header-table {
            margin-bottom: 20px;
            /* Espacio entre encabezado y tabla */
        }

        .header-table td {
            border: none;
            /* Sin bordes para el encabezado */
            vertical-align: middle;
            /* Alinear verticalmente el contenido */
        }

        .date-section {
            text-align: right;
            /* Alinear a la derecha */
            font-size: 8px;
            /* Tamaño de letra para las fechas */
        }
    </style>
</head>

<body>
    <!-- Encabezado -->
    <table class="header-table">
        <tr>
            <td style="width: 10%;">
                <img src="{{ public_path('img/ykm.svg') }}" alt="Logo" style="max-width: 100%; height: auto;">
            </td>
            <td style="width: 60%; text-align: center;">
                <h1 style="margin: 0;">FORECAST VS. FIRME</h1>
            </td>
            <td style="width: 30%; text-align: right;">
                <div class="date-section">
                    @if ($mpPeriod->isNotEmpty())
                    <p><strong>MP PERIOD:</strong> {{ implode(' - ', $mpPeriod->map(function ($item) {
                            return \Carbon\Carbon::createFromFormat('Ymd', $item->DRSDT)->format('d/m/Y') . ' - ' .
                                   \Carbon\Carbon::createFromFormat('Ymd', $item->DREDT)->format('d/m/Y');
                        })->toArray()) }}</p>
                    @endif

                    @if ($sorPeriod->isNotEmpty())
                    <p><strong>SOR PERIOD:</strong> {{ implode(' - ', $sorPeriod->map(function ($item) {
                            return \Carbon\Carbon::createFromFormat('Ymd', $item->DRSDT)->format('d/m/Y') . ' - ' .
                                   \Carbon\Carbon::createFromFormat('Ymd', $item->DREDT)->format('d/m/Y');
                        })->toArray()) }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabla de datos -->
    <table>
        <thead>
            <tr>
                <th>NO.</th>
                <th>PART NO.</th>
                <th>FORECAST</th>
                <th>FIRME</th>
                <th>DEFFERENCE</th>
                <th>RATE %</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['part_no'] }}</td>
                <td>{{ $row['forecast_quantity'] }}</td>
                <td>{{ $row['firm_order_quantity'] }}</td>
                <td>{{ $row['defference_quantity'] }}</td>
                <td>{{ $row['defference_average'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
