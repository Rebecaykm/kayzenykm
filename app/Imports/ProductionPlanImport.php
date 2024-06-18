<?php

namespace App\Imports;

use App\Models\PartNumber;
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
            $partNumber = PartNumber::whereRaw('LTRIM(RTRIM(number)) = ?', [trim($row['no_parte'])])->first();
            $quantity = $row['cantidad'];
            $date = Date::excelToDateTimeObject($row['fecha'])->format('Ymd H:i:s.v');
            $shift = Shift::where('abbreviation', trim($row['turno']))->first();
            $status = Status::where('name', 'PENDIENTE')->first();

            if (!$partNumber) {
                $this->notFoundParts[] = trim($row['no_parte']);
                return null;
            }

            return new ProductionPlan([
                'part_number_id' => $partNumber->id,
                'plan_quantity' => $quantity,
                'date' => $date,
                'shift_id' => $shift->id,
                'status_id' => $status->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error durante la importación del archivo: ' . $e->getMessage());
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
