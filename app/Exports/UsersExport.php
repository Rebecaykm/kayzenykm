<?php

namespace App\Exports;


use App\Models\IIM;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    private $id; // declaras la propiedad

    public function __construct( $id)
    {
        $this->id = $id; // asignas el valor inyectado a la propiedad
    }
    public function view(): View
    {
        $Pr =  $this->id;
        $plan = IIM::query()
            ->select('IPROD')
            ->where([
                ['IREF04', 'like', '%' . $Pr . '%'],
                ['IID', '!=', 'IZ'],
                ['IMPLC', '!=', 'OBSOLETE'],
                ['IPROD', 'NOT LIKE', '%-830%'],
            ])
            ->where(function ($query) {
                $query->where('ICLAS ', 'F1');
            })
            ->distinct('IPROD')
            ->get()->toarray();
        return view('planeacion.RepEstructura', [
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
