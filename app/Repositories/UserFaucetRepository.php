<?php

namespace App\Repositories;

use App\Helpers\Constants;
use App\Helpers\Functions\Faucets;
use App\Models\Faucet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mews\Purifier\Facades\Purifier;

/**
 * Class UserFaucetRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package namespace App\Repositories;
 */
class UserFaucetRepository extends Repository
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
        Faucets::createStoreUserFaucet($data);
    }

    /**
     * Update user data via pivot table.
     *
     * @param array  $data
     * @param $userId
     *
     * @return mixed|void
     */
    public function update(array $data, $userId)
    {
        Faucets::updateUserFaucet($data, $userId);
    }

    /**
     * Soft-delete or permanently delete a user's faucet and referral information.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Faucet $faucet
     * @param bool               $permanentlyDelete
     *
     * @return bool|int
     */
    public function deleteUserFaucet(User $user, Faucet $faucet, bool $permanentlyDelete = false)
    {
        return Faucets::destroyUserFaucet($user, $faucet, $permanentlyDelete);
    }
}
