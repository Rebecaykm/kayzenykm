<?php

namespace App\Exports;

use App\Models\YK007;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportForecastFirmeExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return YK007::query()->orderBy('DRSDT', 'ASC')->get();
    }

    public function headings(): array
    {
        return [
            'PART NO.',
            'START DATE',
            'END DATE',
            'FORECAST',
            'FIRM',
            'DEFFERENCE',
            'RATE %',
        ];
    }

    public function map($yk007): array
    {
        return [
            $yk007->DPROD,
            Carbon::createFromFormat('Ymd', $yk007->DRSDT)->format('d-m-Y'),
            Carbon::createFromFormat('Ymd', $yk007->DREDT)->format('d-m-Y'),
            $yk007->DRFQY,
            $yk007->DROQY,
            $yk007->DRDQY,
            $yk007->DRDRT,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE2E8F0',
                    ],
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => 'dd-mm-yyyy',
            'C' => 'dd-mm-yyyy',
            'D' => '0.0',
            'E' => '0.0',
            'F' => '0.0',
            'G' => '0.00',
        ];
    }
}
