<?php

use App\Helpers\Functions\Users;
use App\Models\User;

Route::get('sitemap-users-faucets', function() {
    $sitemap = App::make('sitemap');

    $users = User::all();

    if (!$sitemap->isCached()) {

        foreach($users as $u){
            $faucets = Users::getFaucets($u);

            foreach($faucets as $f){
                if(!empty($f->pivot->referral_code)){
                    $url = route('users.faucets.show', ['slug' => $u->slug, 'faucetSlug' => $f->slug]);
                    $sitemap->add($url, $f->updated_at->toW3cString(), '1.0', 'daily');
                }
            }
        }

    }

    return $sitemap->render('xml');
});