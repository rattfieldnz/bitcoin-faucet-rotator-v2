<?php

namespace App\Repositories;

use App\Helpers\Functions;
use App\Helpers\Functions\Faucets;
use App\Models\Faucet;
use App\Models\User;
use Helpers\Functions\Users;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;

/**
 * Class UserFaucetRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
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

    /**
     * Create user faucet via pivot table data.
     *
     * @param array $data
     *
     * @return mixed|void
     */
    public function create(array $data)
    {
        $userFaucetData = self::cleanInput($data);
        $userId = $userFaucetData['user_id'];
        $faucetId = $userFaucetData['faucet_id'];
        $referralCode = $userFaucetData['referral_code'];

        $user = User::where('id', $userId)->first();
        $faucet = Faucet::where('id', $faucetId)->first();

        Faucets::setUserFaucetRefCode($user, $faucet, $referralCode);
    }

    /**
     * Update user data via pivot table.
     *
     * @param array $data
     * @param       $userId
     *
     * @return mixed|void
     */
    public function update(array $data, $userId)
    {
        $userFaucetData = self::cleanInput($data);
        $faucetId = $userFaucetData['faucet_id'];
        $referralCode = $userFaucetData['referral_code'];

        $user = User::where('id', $userId)->first();
        $faucet = Faucet::where('id', $faucetId)->first();

        Faucets::setUserFaucetRefCode($user, $faucet, $referralCode);
    }

    /**
     * Sanitize user faucet data via pivot table.
     *
     * @param array $data
     *
     * @return array
     */
    public static function cleanInput(array $data)
    {
        return [
            'user_id' => Purifier::clean($data['user_id'], 'generalFields'),
            'faucet_id' => Purifier::clean($data['faucet_id'], 'generalFields'),
            'referral_code' => Purifier::clean($data['referral_code'], 'generalFields'),
        ];
    }
}
