<?php

namespace App\Exports;

use App\Models\PrivacyPolicy;
use Maatwebsite\Excel\Concerns\FromCollection;

class PrivacyPolicyCsvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PrivacyPolicy::all();
    }
}
