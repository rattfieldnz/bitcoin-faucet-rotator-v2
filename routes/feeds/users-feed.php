<?php

Route::get('users-feed', function(){

    // create new feed
    $feed = App::make("feed");

    // multiple feeds are supported

    // check if there is cached feed and build new only if is not
    if (!$feed->isCached())
    {
        $users = \App\Models\User::where('deleted_at', '=', null)
            ->orderBy('created_at', 'desc')
            ->get();

        // set your feed's title, description, link, pubdate and language
        $feed->title = 'Site Users Feed';
        $feed->description = 'This feed enables you to be updated when new users are added to the rotator.';
        $feed->logo = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $feed->link = url('users-feed');
        $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $feed->pubdate = $users[0]->created_at;
        $feed->lang = 'en';
        $feed->setShortening(true); // true or false
        $feed->setTextLimit(160); // maximum length of description text

        foreach ($users as $u)
        {
            // set item's title, author, url, pubdate, description, content, enclosure (optional)*
            $desc = $u->user_name . " 's Profile";
            $feed->add(
                $u->user_name,
                $u->fullName(),
                route('users.show', ['slug' => $u->slug]),
                $u->created_at,
                $desc,
                $desc
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