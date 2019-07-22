<?php

namespace App\Exports;

use App\Models\MainMeta;
use Maatwebsite\Excel\Concerns\FromCollection;

class MainMetaCsvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MainMeta::all();
    }
}
