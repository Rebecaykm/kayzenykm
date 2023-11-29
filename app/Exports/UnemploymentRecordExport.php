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
            $item['inicio'] = strtoupper($item['time_start']);
            $item['fin'] = strtoupper($item['time_end']);
            $item['minutos'] = strtoupper($item['minutes']);
            $item['fecha'] = Carbon::parse($item['created_at'])->format('d/m/Y H:i:s');
            unset($item['unemployment_type']);
            unset($item['unemployment_name']);
            unset($item['departament_name']);
            unset($item['workcenter_number']);
            unset($item['workcenter_name']);
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
            'HORA INICIO',
            'HHORA FIN',
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
