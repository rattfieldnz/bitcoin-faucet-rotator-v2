<?php

namespace App\Exports;

use App\Helpers\Functions\Faucets;
use App\Helpers\Functions\Users;
use App\Models\Faucet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class FaucetCsvExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection()
    {
        $faucets = new Collection();

        $count = 1;
        foreach (Faucet::all() as $f) {
            $faucets->add([
                'id' => $count,
                'name' => $f->name,
                'url' => $f->url,
                'referral_code' => Faucets::getUserFaucetRefCode(Users::adminUser(), $f),
                'interval_minutes' => (int)$f->interval_minutes,
                'min_payout' => (int)$f->min_payout,
                'max_payout' => (int)$f->max_payout,
                'has_ref_program' => (int)$f->has_ref_program,
                'ref_payout_percent' => (int)$f->ref_payout_percent,
                'comments' => $f->comments,
                'is_paused' => (int)$f->is_paused,
                'meta_title' => $f->meta_title,
                'meta_description' => $f->meta_description,
                'meta_keywords' => $f->meta_keywords,
                'has_low_balance' => (int)$f->has_low_balance,
                'twitter_message' => $f->twitter_message
            ]);
            $count+=1;
        }

        return $faucets;
    }
}
