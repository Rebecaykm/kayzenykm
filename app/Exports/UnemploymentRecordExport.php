<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UnemploymentRecordExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'tipo' => strtoupper($item['unemployment_type']),
                'nombre' => strtoupper($item['unemployment_name']),
                'descripcion' => strtoupper($item['description']),
                'restablecimiento' => strtoupper($item['reset_details']),
                'inicio' => date('d-m-Y H:i:s', strtotime($item['time_start'])),
                'fin' => date('d-m-Y H:i:s', strtotime($item['time_end'])),
                'minutos' => $item['minutes'],
                // 'fecha' => date('d-m-Y H:i:s', strtotime($item['created_at']))
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
            'TIPO',
            'PARO',
            'COMENTARIO',
            'DETALLES DE RESTABLECIMIENTO',
            'HORA INICIO',
            'HORA FIN',
            'MINUTOS',
            // 'FECHA DE REGISTRO'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
