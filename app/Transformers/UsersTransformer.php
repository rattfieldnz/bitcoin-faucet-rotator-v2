<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 9/09/2017
 * Time: 17:07
 */

namespace Transformers;

use App\Models\User;
use App\Transformers\FaucetsTransformer;
use League\Fractal\TransformerAbstract;

/**
 * Class UsersTransformer
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package Transformers
 */
class UsersTransformer extends TransformerAbstract
{
    public function transform(User $user, bool $addFaucets = false)
    {

        $userData = [
            'user_name' => $user->user_name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'bitcoin_address' => $user->bitcoin_address,
        ];

        if ($addFaucets == true) {
            $faucets = [];
            $userFaucets = $user->faucets()
                ->where('is_paused', '=', false)
                ->where('has_low_balance', '=', false)
                ->where('faucets.deleted_at', '=', null)
                ->orderBy('interval_minutes')
                ->get();

            foreach ($userFaucets as $f) {
                array_push($faucets, (new FaucetsTransformer)->transform($user, $f, true));
            }

            $userData['faucets'] = $faucets;
        }

        return $userData;
    }
}
