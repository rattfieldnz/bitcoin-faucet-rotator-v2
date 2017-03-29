<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 18/03/2017
 * Time: 19:43
 */

namespace App\Helpers;


use App\Models\User;

class Functions
{
    public static function userCanAccessArea(User $user, $routeName, array $dataParameters = null){
        if($user->is_admin == false || !$user->hasRole('owner')){
            abort(403);
        }
        $currentRoute = route($routeName, $dataParameters);
        if(!$currentRoute){
            abort(404);
        }
        return redirect($currentRoute)->with($dataParameters);
    }
}