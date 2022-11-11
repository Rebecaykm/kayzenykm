<?php

namespace App\Exports;

use App\Http\Controllers\Structure;
use App\User;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'final',
            'componente',
            'clase',
            'Activo'
        ];
    }
    public function collection()
    {
         $mostar = Structure::Query()
         ->select( 'final', 'componente','clase', 'Activo')
         ->orderby('componente');
         return $mostar;

    }
    public function view(): view
    {
    }
}
