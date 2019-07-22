<?php

namespace App\Exports;

use App\Models\AdBlock;
use Maatwebsite\Excel\Concerns\FromCollection;

class AdBlockCsvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AdBlock::all();
    }
}
