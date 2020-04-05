<?php

use App\Helpers\Functions\Users;
use App\Models\User;
use Carbon\Carbon;

Route::get('sitemap-users-rotators', function() {
    $sitemap = App::make('sitemap');

    $users = User::all();

    if (!$sitemap->isCached()) {

        foreach($users as $u){

            $url = route('users.rotator', ['slug' => $u->slug]);
            $lastFaucet = Users::getFaucets($u)
                ->sortByDesc('updated_at')
                ->first();

            $dateString = !empty($lastFaucet) && !empty($lastFaucet->updated_at) ?
                $lastFaucet->updated_at->toW3cString() :
                Carbon::now()->toW3cString();

            $sitemap->add($url, $dateString, '1.0', 'daily');
        }

    }

    return $sitemap->render('xml');
});