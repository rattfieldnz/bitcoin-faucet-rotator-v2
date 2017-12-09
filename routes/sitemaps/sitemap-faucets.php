<?php

Route::get('sitemap-faucets', function() {
    $sitemap = App::make('sitemap');

    if (!$sitemap->isCached()) {

        $faucets = \App\Models\Faucet::all();

        foreach($faucets as $f){
            $url = route('faucets.show', ['slug' => $f->slug]);
            $sitemap->add($url, $f->updated_at->toW3cString(), '1.0', 'daily');
        }
    }

    return $sitemap->render('xml');
});