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
    public static function userCanAccessArea(User $user, $route, array $parameters = null, array $dataParameters = null){
        //dd(route($route, $parameters));
        if(!$user){
            return redirect(route('login'));
        }
        if(!route($route, $parameters)){
            abort(404);
        }
        if($user->is_admin == false){
            abort(403);
        }
        return redirect(route($route, $parameters))->with($dataParameters);
    }
}