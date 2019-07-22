<?php

namespace App\Exports;

use App\Models\PaymentProcessor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentProcessorCsvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $paymentProcessors = new Collection();

        $count = 1;
        foreach (PaymentProcessor::all() as $p) {
            $paymentProcessors->add([
                'id' => $count,
                'name' => $p->name,
                'url' => $p->url,
                'slug' => $p->slug,
                'meta_title' => $p->meta_title,
                'meta_description' => $p->meta_description,
                'meta_keywords' => $p->meta_description
            ]);
            $count+=1;
        }

        return $paymentProcessors;
    }
}
