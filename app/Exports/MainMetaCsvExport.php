<?php

namespace App\Exports;

use App\Models\MainMeta;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class MainMetaCsvExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return MainMeta::all();
    }
}
