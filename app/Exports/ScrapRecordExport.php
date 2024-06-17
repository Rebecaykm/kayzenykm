<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScrapRecordExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                'departamento' => strtoupper($item['departament_name']),
                'linea' => strtoupper($item['line_name']),
                'estacion' => strtoupper($item['workcenter_number']),
                'nom' => strtoupper($item['workcenter_name']),
                'parte' => strtoupper($item['number_part']),
                'tipo' => strtoupper($item['type_scrap']),
                'codigo' => strtoupper($item['scrap_code']),
                'nombre' => strtoupper($item['scrap_name']),
                'cantidad' => strtoupper($item['scrap_quantity']),
                'usuario' => strtoupper($item['user_name']),
                'fecha' => date('d-m-Y H:i:s', strtotime($item['created_at'])),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'DEPARTAMENTO',
            'LÍNEA',
            'NO ESTACIÓN',
            'NOMBRE DE ESTACIÓN',
            'NÚMERO DE PARTE',
            'TIPO',
            'CÓDIGO',
            'NOMBRE',
            'CANTIDAD',
            'USUARIO',
            'FECHA DE REGISTRO'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
