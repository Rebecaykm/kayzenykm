<?php

namespace App\Imports;

use App\Models\PartNumber;
use App\Models\PressPartNumber;
use App\Models\ProductionPlan;
use App\Models\Shift;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductionPlanImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithEvents
{
    use RegistersEventListeners;

    protected $notFoundParts = [];

    public function model(array $row)
    {
        try {
            $noParte = trim($row['no_parte']);
            $quantity = $row['cantidad'];
            $date = Date::excelToDateTimeObject($row['fecha'])->format('Y-m-d');
            $shift = Shift::where('abbreviation', trim($row['turno']))->first();
            $status = Status::where('name', 'PENDIENTE')->first();

            if (strpos($noParte, '/') !== false) {
                $pressPartNumber = PressPartNumber::query()
                    ->select([
                        'press_part_numbers.id as press_part_number_id',
                        'press_part_numbers.part_number as press_part_number',
                        'part_numbers.id as part_number_id',
                        'part_numbers.number as part_number'
                    ])
                    ->join('part_number_press_part_number', 'press_part_numbers.id', '=', 'part_number_press_part_number.press_part_number_id')
                    ->join('part_numbers', 'part_number_press_part_number.part_number_id', '=', 'part_numbers.id')
                    ->whereRaw('LTRIM(RTRIM(press_part_numbers.part_number)) = ?', [$noParte])
                    ->get();

                if ($pressPartNumber->isEmpty()) {
                    $this->notFoundParts[] = $noParte;
                    return null;
                }

                $newPlans = [];

                foreach ($pressPartNumber as $part) {
                    $newPlans[] = [
                        'part_number_id' => $part->part_number_id,
                        'plan_quantity' => $quantity,
                        'date' => $date,
                        'shift_id' => $shift->id,
                        'status_id' => $status->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                ProductionPlan::insert($newPlans);
            } else {
                $partNumber = PartNumber::whereRaw('LTRIM(RTRIM(number)) = ?', [trim($row['no_parte'])])->first();

                if (!$partNumber) {
                    $this->notFoundParts[] = $noParte;
                    return null;
                }

                return new ProductionPlan([
                    'part_number_id' => $partNumber->id,
                    'plan_quantity' => $quantity,
                    'date' => $date,
                    'shift_id' => $shift->id,
                    'status_id' => $status->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error durante la importación del archivo: ' . $e->getMessage(), [
                'row' => $row,
                'exception' => $e
            ]);
            return null;
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public static function afterImport(\Maatwebsite\Excel\Events\AfterImport $event)
    {
        $instance = $event->getConcernable();
        if (!empty($instance->notFoundParts)) {
            Session::flash('not_found_parts', $instance->notFoundParts);
        }
    }
}
