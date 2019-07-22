<?php

namespace App\Exports;

use App\Models\TermsAndConditions;
use Maatwebsite\Excel\Concerns\FromCollection;

class TermsAndConditionsCsvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TermsAndConditions::all();
    }
}
