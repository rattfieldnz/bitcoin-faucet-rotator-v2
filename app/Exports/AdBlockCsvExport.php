<?php

namespace App\Exports;

use App\Models\AdBlock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class AdBlockCsvExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return AdBlock::all();
    }
}
