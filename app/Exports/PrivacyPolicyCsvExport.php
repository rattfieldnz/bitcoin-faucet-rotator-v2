<?php

namespace App\Exports;

use App\Models\PrivacyPolicy;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PrivacyPolicyCsvExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return PrivacyPolicy::all();
    }
}
