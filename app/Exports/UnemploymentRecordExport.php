<?php

namespace App\Exports;

use App\Models\UnemploymentRecord;
use Carbon\Carbon;
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
            $item['tipo'] = strtoupper($item['unemployment_type']);
            $item['nombre'] = strtoupper($item['unemployment_name']);
            $item['departamento'] = strtoupper($item['departament_name']);
            $item['estacion'] = strtoupper($item['workcenter_number']);
            $item['nom'] = strtoupper($item['workcenter_name']);
            $item['descripcion'] = strtoupper($item['description']);
            $item['inicio'] = date('d-m-Y H:i:s', strtotime($item['time_start']));
            $item['fin'] = date('d-m-Y H:i:s', strtotime($item['time_end']));
            $item['minutos'] = $item['minutes'];
            $item['fecha'] = date('d-m-Y H:i:s', strtotime($item['created_at']));
            unset($item['unemployment_type']);
            unset($item['unemployment_name']);
            unset($item['departament_name']);
            unset($item['workcenter_number']);
            unset($item['workcenter_name']);
            unset($item['description']);
            unset($item['time_start']);
            unset($item['time_end']);
            unset($item['minutes']);
            unset($item['created_at']);
            return $item;
        });
    }

    public function headings(): array
    {
        return [
            'TIPO',
            'PARO',
            'DEPARTAMENTO',
            'NO ESTACIÓN',
            'NOMBRE DE ESTACIÓN',
            'DESCRIPCION',
            'HORA INICIO',
            'HORA FIN',
            'MINUTOS',
            'FECHA DE REGISTRO'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
