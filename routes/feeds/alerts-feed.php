<?php

Route::get('alerts-feed', function() {

    // create new feed
    $feed = App::make("feed");

    // multiple feeds are supported
    // if you are using caching you should set different cache keys for your feeds

    // check if there is cached feed and build new only if is not
    if (!$feed->isCached())
    {
        $alerts = \App\Models\Alert::where('deleted_at', '=', null)
            ->orderBy('created_at', 'desc')
            ->get();

        // set your feed's title, description, link, pubdate and language
        $feed->title = 'Alerts Feed';
        $feed->description = 'This feed enables you to be updated when new alerts are published.';
        $feed->logo = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $feed->link = url('alerts-feed');
        $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $feed->pubdate = !empty($alerts[0]) ? $alerts[0]->created_at : \Carbon\Carbon::now();
        $feed->lang = 'en';
        $feed->setShortening(true); // true or false
        $feed->setTextLimit(160); // maximum length of description text

        foreach ($alerts as $a)
        {
            // set item's title, author, url, pubdate, description, content, enclosure (optional)*
            $feed->add(
                $a->title,
                \App\Helpers\Functions\Users::adminUser()->fullName(),
                route('alerts.show', ['slug' => $a->slug]),
                $a->created_at,
                $a->title,
                $a->summary
            );
        }
    }

    // first param is the feed format
    // optional: second param is cache duration (value of 0 turns off caching)
    // optional: you can set custom cache key with 3rd param as string
    $feed->ctype = "text/xml";
    return $feed->render('rss');

});