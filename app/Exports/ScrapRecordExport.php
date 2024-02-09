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
            $item['tipo'] = strtoupper($item['type_scrap']);
            $item['codigo'] = strtoupper($item['scrap_code']);
            $item['nombre'] = strtoupper($item['scrap_name']);
            $item['cantidad'] = strtoupper($item['scrap_quatity']);
            $item['parte'] = strtoupper($item['number_part']);
            $item['departamento'] = strtoupper($item['departament_name']);
            $item['usuario'] = strtoupper($item['user_name']);
            $item['fecha'] = date('d-m-Y H:i:s', strtotime($item['created_at']));
            unset($item['type_scrap']);
            unset($item['scrap_code']);
            unset($item['scrap_name']);
            unset($item['scrap_quatity']);
            unset($item['number_part']);
            unset($item['departament_name']);
            unset($item['user_name']);
            unset($item['created_at']);
            return $item;
        });
    }

    public function headings(): array
    {
        return [
            'TIPO',
            'CÓDIGO',
            'NOMBRE',
            'CANTIDAD',
            'NÚMERO DE PARTE',
            'DEPARTAMENTO',
            'USUARIO',
            'FECHA DE REGISTRO'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, 
        ];
    }
}
