<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 18/03/2017
 * Time: 19:43
 */

namespace App\Helpers;

use App\Models\Faucet;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class Functions
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Helpers
 */
class Functions
{
    /**
     * Function to see if user can access route/area.
     *
     * @param \App\Models\User $user
     * @param $routeName
     * @param array            $routeParameters
     * @param array|null       $dataParameters
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function userCanAccessArea(User $user, $routeName, array $routeParameters, array $dataParameters = null)
    {
        if ($user->is_admin == false || !$user->hasRole('owner')) {
            abort(403);
        }
        $currentRoute = route($routeName, $routeParameters);
        if (!$currentRoute) {
            abort(404);
        }
        return redirect($currentRoute)->with($dataParameters);
    }

    /**
     * Get user's faucet referral code.
     *
     * @param  User   $user
     * @param  Faucet $faucet
     * @return string
     */
    public static function getUserFaucetRefCode(User $user, Faucet $faucet)
    {

        // Check if the user and faucet exists.
        if (empty($user) || empty($faucet)) {
            return null;
        }

        $referralCode = DB::table('referral_info')->where(
            [
                ['faucet_id', '=', $faucet->id],
                ['user_id', '=', $user->id]
            ]
        )->first();

        return $referralCode != null ? $referralCode->referral_code : null;
    }

    /**
     * Set user's faucet referral code.
     *
     * @param  User   $user
     * @param  Faucet $faucet
     * @param  string $refCode
     * @return null
     */
    public static function setUserFaucetRefCode(User $user, Faucet $faucet, $refCode = null)
    {

        // Check if the user and faucet exists.
        if (empty($user) || empty($faucet)) {
            return null;
        }

        // Check if the user already has a matching ref code.
        $referralCode = self::getUserFaucetRefCode($user, $faucet);

        // If there is no matching ref code, add record to database.
        if ($referralCode == null || $referralCode == '' || empty($referralCode)) {
            DB::table('referral_info')->insert(
                ['faucet_id' => $faucet->id, 'user_id' => $user->id, 'referral_code' => $refCode]
            );
        } else {
            DB::table('referral_info')->where(
                [
                    ['faucet_id', '=', $faucet->id],
                    ['user_id', '=', $user->id]
                ]
            )->update(['referral_code' => $refCode]);
        }
    }
}
