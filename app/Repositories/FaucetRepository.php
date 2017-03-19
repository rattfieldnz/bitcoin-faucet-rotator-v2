<?php

namespace App\Repositories;

use App\Models\Faucet;
use InfyOm\Generator\Common\BaseRepository;

class FaucetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'url',
        'interval_minutes',
        'min_payout',
        'max_payout',
        'has_ref_program',
        'ref_payout_percent',
        'comments',
        'is_paused',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'has_low_balance'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Faucet::class;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * NOTE: New users cannot register as an admin.
     *
     * @param  array  $data
     * @return Faucet
     */
    public function create(array $data)
    {
        // Have to skip presenter to get a model not some data
        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $faucetData = [
            'name' => $data['name'],
            'url' => $data['url'],
            'interval_minutes' => $data['interval_minutes'],
            'min_payout' => $data['min_payout'],
            'max_payout' => $data['max_payout'],
            'has_ref_program' => $data['has_ref_program'],
            'ref_payout_percent' => $data['ref_payout_percent'],
            'comments' => $data['comments'],
            'is_paused' => $data['comments'],
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'],
            'meta_keywords' => $data['meta_keywords'],
            'has_low_balance'  => $data['has_low_balance']
        ];
        $faucet = Faucet::create($faucetData);
        $this->skipPresenter($temporarySkipPresenter);
        $this->updateRelations($faucet, $faucetData);
        $faucet->save();
        return $this->parserResult($faucet);
    }
}
