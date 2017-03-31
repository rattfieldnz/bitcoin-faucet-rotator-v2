<?php

namespace App\Repositories;

use App\Models\Faucet;
use Mews\Purifier\Facades\Purifier;

/**
 * Class UserFaucetRepository
 * @package namespace App\Repositories;
 */
class UserFaucetRepository extends Repository implements IRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'faucet_id',
        'referral_code'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Faucet::class;
    }

    public function create(array $data)
    {

    }

    public function update(array $data, $id)
    {

    }

    static function cleanInput(array $data)
    {
        return [
            'user_id' => Purifier::clean($data['user_id'], 'generalFields'),
            'faucet_id' => Purifier::clean($data['faucet_id'], 'generalFields'),
            'referral_code' => Purifier::clean($data['referral_code'], 'generalFields'),
        ];
    }
}
