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

            $sitemap->add($url, $lastFaucet->updated_at->toW3cString(), '1.0', 'daily');
        }

    }

    return $sitemap->render('xml');
});