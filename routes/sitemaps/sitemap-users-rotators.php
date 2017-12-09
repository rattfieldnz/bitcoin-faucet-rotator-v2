<?php

Route::get('sitemap-users-rotators', function() {
    $sitemap = App::make('sitemap');

    $users = \App\Models\User::all();

    if (!$sitemap->isCached()) {

        foreach($users as $u){

            $url = route('users.rotator', ['userSlug' => $u->slug]);
            $lastFaucet = \App\Helpers\Functions\Users::getFaucets($u)
                ->sortByDesc('updated_at')
                ->first();

            $dateString = !empty($lastFaucet) && !empty($lastFaucet->updated_at) ?
                $lastFaucet->updated_at->toW3cString() :
                \Carbon\Carbon::now()->toW3cString();

            $sitemap->add($url, $dateString, '1.0', 'daily');
        }

    }

    return $sitemap->render('xml');
});