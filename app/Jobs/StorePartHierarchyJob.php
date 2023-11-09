<?php

namespace App\Jobs;

use App\Models\PartNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StorePartHierarchyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $main;
    private $sub;
    private $qty;

    /**
     * Create a new job instance.
     */
    public function __construct($main, $sub, $qty)
    {
        $this->main = $main;
        $this->sub = $sub;
        $this->qty = $qty;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mainPart = PartNumber::where('number', $this->main)->first();
        $subPart = PartNumber::where('number', $this->sub)->first();

        if ($mainPart && $subPart) {
            $mainPart->subPartNumbers()->syncWithoutDetaching([$subPart->id => ['required_quantity' => $this->qty]]);
        } elseif (!$mainPart) {
            Log::info("Número de parte no encontrado: " . $this->main);
        } else {
            Log::info("Número de parte no encontrada: " . $this->sub);
        }
    }
}
