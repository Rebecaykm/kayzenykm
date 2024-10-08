<!DOCTYPE html>
<html>
<head>
    <title>Reporte PDF</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif; /* Asegúrate de que este sea el nombre correcto de la fuente */
            font-size: 8px; /* Tamaño de letra para el contenido */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px; /* Espacio entre encabezado y tabla */
        }
        th, td {
            border: 1px solid black;
            padding: 2px; /* Espaciado vertical de 2px */
            text-align: left;
        }
        th {
            background-color: #e2e8f0;
            font-weight: bold; /* Encabezados en negrita */
            font-size: 10px; /* Tamaño de letra para los encabezados */
        }
        .header-table {
            margin-bottom: 20px; /* Espacio entre encabezado y tabla */
        }
        .header-table td {
            border: none; /* Sin bordes para el encabezado */
            vertical-align: middle; /* Alinear verticalmente el contenido */
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
            <td style="width: 15%; text-align: right;">{{ now()->format('d-m-Y H:i:s') }}</td>
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
