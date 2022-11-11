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

    public function view(): view
    {
        return view('exports.invoices', [
            'invoices' => Invoice::all()
        ]);
    }
}
