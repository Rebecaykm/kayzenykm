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
            $item['area'] = strtoupper(trim($item['name_departament']));
            $item['linea'] = strtoupper(trim($item['name_line']));
            $item['no_estacion'] = strtoupper(trim($item['number_workcenter']));
            $item['estacion'] = strtoupper(trim($item['name_workcenter']));
            $item['no_parte'] = strtoupper(trim($item['number_part_number']));
            $item['secuencia'] = strtoupper(trim($item['sequence']));
            $item['cantidad'] = strtoupper(trim($item['quantity']));
            $item['fecha'] = strtoupper(trim(Carbon::parse($item['date_production'])->format('d-m-Y')));
            $item['turno'] = strtoupper(trim($item['abbreviation_shift']));
            $item['inicio'] = date('d-m-Y H:i:s', strtotime($item['time_start']));
            $item['fin'] = date('d-m-Y H:i:s', strtotime($item['time_end']));
            $item['minutos'] = strtoupper($item['minutes']);
            $item['estado'] = strtoupper(trim($item['name_status']));
            $item['registro'] = date('d-m-Y H:i:s', strtotime($item['created_at']));
            unset($item['name_departament']);
            unset($item['name_line']);
            unset($item['number_workcenter']);
            unset($item['name_workcenter']);
            unset($item['number_part_number']);
            unset($item['sequence']);
            unset($item['quantity']);
            unset($item['date_production']);
            unset($item['abbreviation_shift']);
            unset($item['time_start']);
            unset($item['time_end']);
            unset($item['minutes']);
            unset($item['created_at']);
            unset($item['name_status']);
            return $item;
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
            'HORA DE INICIO',
            'HORA DE FIN',
            'CANT. MINUTOS',
            'ESTADO',
            'FECHA DE REGISTRO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
