<?php

namespace App\Jobs;

use App\Models\IIM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PartNumberMigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //                                  Nombre,  Numero,  Balance, Medida, Tipo,   Clase,   Paquete,   Cantidad, Planeador, Projecto, Creacion dia y hora
        $partNumbers = IIM::query()
            ->select('IDESC', 'IPROD', 'IOPB', 'IUMS', 'IITYP', 'ICLAS', 'IMSPKT', 'IMBOXQ', 'IBUYC', 'IREF04', 'IMENDT', 'IMENTM')
            ->where([['IID', '!=', 'IZ'], ['IMPLC', '!=', 'OBSOLETE']])
            ->orderBy('IMENDT', 'ASC')
            ->get();

        foreach ($partNumbers as $key => $partNumber) {

            StorePartNumberJob::dispatch(
                preg_replace('([^A-Za-z0-9])', '', $partNumber->IDESC),
                preg_replace('([^A-Za-z0-9])', '', $partNumber->IPROD),
                // $partNumber->IOPB,
                $partNumber->IUMS,
                $partNumber->IITYP,
                $partNumber->ICLAS,
                $partNumber->IMSPKT,
                $partNumber->IMBOXQ,
                $partNumber->IBUYC,
                $partNumber->IREF04,
                // $partNumber->IMENDT,
                // $partNumber->IMENTM
            );
        }
    }
}
