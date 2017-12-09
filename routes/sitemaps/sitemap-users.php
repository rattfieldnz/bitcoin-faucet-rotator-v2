<?php

Route::get('sitemap-users', function() {
    $sitemap = App::make('sitemap');

    if (!$sitemap->isCached()) {

        $users = \App\Models\User::all();

        foreach($users as $u){
            $url = route('users.show', ['slug' => $u->slug]);
            $sitemap->add($url, $u->updated_at->toW3cString(), '1.0', 'daily');
        }
    }

    return $sitemap->render('xml');
});