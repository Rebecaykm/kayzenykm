<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdcutionRecordExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            $item['name_departament'] = strtoupper($item['name_departament']);
            $item['number_workcenter'] = strtoupper($item['number_workcenter']);
            $item['name_workcenter'] = strtoupper($item['name_workcenter']);
            $item['number_part_number'] = strtoupper($item['number_part_number']);
            $item['sequence'] = strtoupper($item['sequence']);
            $item['quantity'] = strtoupper($item['quantity']);
            $item['time_start'] = strtoupper($item['time_start']);
            $item['time_end'] = strtoupper($item['time_end']);
            $item['minutes'] = strtoupper($item['minutes']);
            $item['created_at'] = Carbon::parse($item['created_at'])->format('Y-m-d H:i:s');
            $item['name_status'] = strtoupper($item['name_status']);
            return $item;
        });
    }

    public function headings(): array
    {
        return [
            'DEPARTAMENTO',
            'NO. ESTACIÓN',
            'NOMBRE ESTACIÓN',
            'NÚMERO DE PARTE',
            'SECUENCIA',
            'CANTIDAD',
            'HORA DE INICIO',
            'HORA DE FIN',
            'CANT. MINUTOS',
            'FECHA DE REGISTRO',
            'ESTADO'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
