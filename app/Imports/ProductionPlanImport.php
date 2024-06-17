<?php

namespace App\Imports;

use App\Models\PartNumber;
use App\Models\ProductionPlan;
use App\Models\Shift;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProductionPlanImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            $partNumber = PartNumber::whereRaw('LTRIM(RTRIM(number)) = ?', [trim($row['no_parte'])])->first();
            $quantity = $row['cantidad'];
            $date = Date::excelToDateTimeObject($row['fecha'])->format('Ymd H:i:s.v');
            $shift = Shift::where('abbreviation', trim($row['turno']))->first();
            $status = Status::where('name', 'PENDIENTE')->first();

            if (!$partNumber) {
                throw new \Exception('Número de parte no encontrado para: ' . $row['no_parte']);
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
        return 1000;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
