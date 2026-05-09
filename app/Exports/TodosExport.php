<?php

namespace App\Exports;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\FromCollection;

class TodosExport implements FromCollection
{
    public function collection()
    {
        return Todo::all();
    }
}