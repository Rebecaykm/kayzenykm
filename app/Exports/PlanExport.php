<?php

namespace App\Exports;


use App\Models\LOGSUP;
use App\Models\Iim;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PlanExport implements FromView
{
    private $id; // declaras la propiedad
    private $fecha;
    private $fechaFin;
    public function __construct( $fecha,$fechaFin )
    {
        // $this->id = $id;
        $this->fecha = $fecha;
        $this->fechaFin = $fechaFin; // asignas el valor inyectado a la propiedad
    }
    public function view(): View
    {
        $Pr =  $this->id;
        $FechaCam =  $this->fecha;
        $FechaFinCam=  $this->fechaFin;
        $plan = LOGSUP::query()
            ->select(
                'K6PROD',
                'K6WRKC',
                'K6SDTE',
                'K6EDTE',
                'K6DDTE',
                'K6DSHT',
                'K6PFQY',
                'K6CUSR',
                'K6CCDT',
                'K6CCTM',
                'K6FIL1',
                'K6FIL2'
            )
            ->where([
                ['K6DDTE', '>=', $FechaCam],
                ['K6DDTE', '<=', $FechaFinCam],
            ])
            ->unique('K6DDT','K6DSHT')->get()->toarray();
            DD($plan );

        return view('planeacion.RepPlan', [
            'plan' => $plan
        ]);
    }
}
// class UsersExport implements FromView
// {
//     /**
//      * @return \Illuminate\Support\Collection
//      */
//     public function headings(): array
//     {
//         return [
//             'final',
//             'componente',
//             'clase',
//             'Activo'
//         ];
//     }

//     public function view(): view
//     {
//         return view();
//     }
// }
