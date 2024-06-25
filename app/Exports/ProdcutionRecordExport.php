<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdcutionRecordExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                'area' => strtoupper(trim($item['name_departament'])),
                'linea' => strtoupper(trim($item['name_line'])),
                'no_estacion' => strtoupper(trim($item['number_workcenter'])),
                'estacion' => strtoupper(trim($item['name_workcenter'])),
                'no_parte' => strtoupper(trim($item['number_part_number'])),
                'secuencia' => strtoupper(trim($item['sequence'])),
                'cantidad' => strtoupper(trim($item['quantity'])),
                'fecha' => strtoupper(trim(Carbon::parse($item['date_production'])->format('d-m-Y'))),
                'turno' => strtoupper(trim($item['abbreviation_shift'])),
                'inicio' => date('d-m-Y H:i:s', strtotime($item['time_start'])),
                'fin' => date('d-m-Y H:i:s', strtotime($item['time_end'])),
                'minutos' => strtoupper(trim($item['minutes'])),
                'estado' => strtoupper(trim($item['name_status'])),
                // 'registro' => date('d-m-Y H:i:s', strtotime($item['created_at'])),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ÁREA',
            'LÍNEA',
            'NO. ESTACIÓN',
            'ESTACIÓN',
            'NO. DE PARTE',
            'SECUENCIA',
            'CANTIDAD',
            'FECHA',
            'TURNO',
            'INICIO',
            'FIN',
            'MIN PROCESADOS',
            'ESTADO',
            // 'FECHA DE REGISTRO',
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
            'J' => NumberFormat::FORMAT_DATE_DATETIME,
            'K' => NumberFormat::FORMAT_DATE_DATETIME,
            'L' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
