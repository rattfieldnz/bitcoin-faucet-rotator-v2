<?php

namespace App\Exports;

use App\Models\TermsAndConditions;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class TermsAndConditionsCsvExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return TermsAndConditions::all();
    }
}
