<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ProdcutionRecord extends Model
{
    use HasFactory;

    protected $dateFormat = 'Ymd H:i:s.v';

    protected $fillable = [
        'id', 'part_number_id', 'quantity', 'sequence', 'time_start', 'time_end', 'minutes', 'status_id', 'production_plan_id', 'user_id'
    ];

    /**
     *
     */
    public function productionPlan(): BelongsTo
    {
        return $this->belongsTo(ProductionPlan::class);
    }

    /**
     *
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     *
     */
    public static function storeProductionRecord(int $partNumberId, int $snpQuantity, string $timeStart, string $timeEnd, string $minutes, int $productionPlanId, int $quantity, int $prodcutionRecordStatus)
    {
        $prodcutionRecord = ProdcutionRecord::where('production_plan_id', $productionPlanId)->first();

        if ($prodcutionRecord === null) {
            $status = Status::where('name', 'EN PROCESO')->first();

            $x = ProdcutionRecord::create([
                'part_number_id' => $partNumberId,
                'quantity' => $snpQuantity,
                'sequence' => '001',
                'time_start' => $timeStart,
                'time_end' => $timeEnd,
                'minutes' => $minutes,
                'status_id' => $prodcutionRecordStatus,
                'production_plan_id' => $productionPlanId,
                'user_id' => Auth::id()
            ]);

            $productionPlan = ProductionPlan::find($productionPlanId);
            $total = $productionPlan->production_quantity + $quantity;
            $productionPlan->update(['production_quantity' => $total, 'status_id' => $status->id]);

            return $x;
        } else {
            $prodcutionRecord = ProdcutionRecord::where('production_plan_id', $productionPlanId)->orderBy('sequence', 'DESC')->first();
            $seq = $prodcutionRecord->sequence + 1;

            $x = ProdcutionRecord::create([
                'part_number_id' => $partNumberId,
                'quantity' => $snpQuantity,
                'sequence' => str_pad($seq, 3, "0", STR_PAD_LEFT),
                'time_start' => $timeStart,
                'time_end' => $timeEnd,
                'minutes' => $minutes,
                'status_id' => $prodcutionRecordStatus,
                'production_plan_id' => $productionPlanId,
                'user_id' => Auth::id()
            ]);

            $productionPlan = ProductionPlan::find($productionPlanId);
            $total = $productionPlan->production_quantity + $quantity;
            $productionPlan->update(['production_quantity' => $total]);

            return $x;
        }
    }
}
