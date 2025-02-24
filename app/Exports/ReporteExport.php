<?php

namespace App\Exports;

use App\Models\ProductionPlan;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ReporteExport implements WithEvents
{
    protected $workCenterId;
    protected $shiftId;
    protected $date;

    public function __construct($workCenterId, $shiftId, $date)
    {
        $this->workCenterId = $workCenterId;
        $this->shiftId = $shiftId;
        $this->date = $date;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $productionPlans = ProductionPlan::query()
                    ->select(
                        'production_plans.id as id',
                        'lines.name as lineName',
                        'workcenters.name as workName',
                        'part_numbers.number as partNumber',
                        'production_plans.plan_quantity as planQuantity',
                        'production_plans.production_quantity as productionQuantity',
                        'production_plans.date as planDate',
                        'shifts.name as shiftName'
                    )
                    ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
                    ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
                    ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
                    ->join('lines', 'workcenters.line_id', '=', 'lines.id')
                    ->where('production_plans.shift_id', $this->shiftId)
                    ->where('production_plans.date', $this->date)
                    ->where('workcenters.id', $this->workCenterId)
                    ->get();

                $grouped = [];

                $productionPlans = ProductionPlan::query()
                    ->select(
                        'production_plans.id as id',
                        'lines.name as lineName',
                        'workcenters.name as workName',
                        'part_numbers.number as partNumber',
                        'production_plans.plan_quantity as planQuantity',
                        'production_plans.production_quantity as productionQuantity',
                        'production_plans.date as planDate',
                        'shifts.name as shiftName'
                    )
                    ->join('shifts', 'production_plans.shift_id', '=', 'shifts.id')
                    ->join('part_numbers', 'production_plans.part_number_id', '=', 'part_numbers.id')
                    ->join('workcenters', 'part_numbers.workcenter_id', '=', 'workcenters.id')
                    ->join('lines', 'workcenters.line_id', '=', 'lines.id')
                    ->where('production_plans.shift_id', $this->shiftId)
                    ->where('production_plans.date', $this->date)
                    ->where('workcenters.id', $this->workCenterId)
                    ->get();

                $grouped = $productionPlans->groupBy(function ($item) {
                    // Agrupar solo por lineName, workName, y shiftName (sin concatenar planDate)
                    return $item->lineName . '-' . $item->workName . '-' . $item->shiftName . '-' . $item->planDate;
                })->map(function ($group) {
                    // Obtenemos los valores de lineName, workName y shiftName directamente
                    $keys = explode('-', $group->first()->lineName . '-' . $group->first()->workName . '-' . $group->first()->shiftName . '-' . $group->first()->planDate);
                    return [
                        'lineName' => $keys[0],
                        'workName' => $keys[1],
                        'shiftName' => $keys[2],
                        'planDate' => $keys[3] . '-' . $keys[4] . '-' . $keys[5],
                        'productionPlan' => $group->map(function ($item) {
                            return [
                                'partNumber' => $item->partNumber,
                                'planQuantity' => $item->planQuantity,
                                'productionQuantity' => $item->productionQuantity,
                            ];
                        }),
                    ];
                })->values()->all();

                $path = storage_path('files/forma.xlsx');
                if (!file_exists($path)) {
                    dd("El archivo no se encuentra en la ruta especificada.");
                }

                $sheet = $event->sheet;

                if (!empty($grouped)) {
                    $sheet->setCellValue('D9', $grouped[0]['lineName']);
                    $sheet->setCellValue('H9', $grouped[0]['workName']);
                    $sheet->setCellValue('H9', $grouped[0]['shiftName']);
                    $sheet->setCellValue('D11', $grouped[0]['shiftName']);
                    $sheet->setCellValue('H11', $grouped[0]['planDate']);
                } else {
                    dd("No hay datos para escribir en el archivo Excel.");
                }
            }
        ];
    }
}
