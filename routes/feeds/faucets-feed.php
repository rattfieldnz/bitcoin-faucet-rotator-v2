<?php

Route::get('faucets-feed', function(){

    // create new feed
    $feed = App::make("feed");

    // multiple feeds are supported
    // if you are using caching you should set different cache keys for your feeds

    // check if there is cached feed and build new only if is not
    if (!$feed->isCached())
    {
        $adminUser = \App\Helpers\Functions\Users::adminUser();
        $faucets = \App\Models\Faucet::where('deleted_at', '=', null)
            ->orderBy('created_at', 'desc')
            ->get();

        // set your feed's title, description, link, pubdate and language
        $feed->title = 'Bitcoin Faucets Feed';
        $feed->description = 'This feed enables you to be updated when new faucets are added to the rotator.';
        $feed->logo = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $feed->link = url('faucets-feed');
        $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $feed->pubdate = $faucets[0]->created_at;
        $feed->lang = 'en';
        $feed->setShortening(true); // true or false
        $feed->setTextLimit(160); // maximum length of description text

        foreach ($faucets as $f)
        {
            // set item's title, author, url, pubdate, description, content, enclosure (optional)*
            $feed->add(
                $f->name,
                $adminUser->fullName(),
                route('faucets.show', ['slug' => $f->slug]),
                $f->created_at,
                $f->meta_title,
                $f->meta_description
            );
        }

    }

    // first param is the feed format
    // optional: second param is cache duration (value of 0 turns off caching)
    // optional: you can set custom cache key with 3rd param as string
    $feed->ctype = "text/xml";
    return $feed->render('rss');

    // to return your feed as a string set second param to -1
    // $xml = $feed->render('atom', -1);

});