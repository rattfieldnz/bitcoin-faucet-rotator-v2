<?php

Route::get('payment-processors-feed', function(){

    // create new feed
    $feed = App::make("feed");

    // multiple feeds are supported
    // if you are using caching you should set different cache keys for your feeds

    // check if there is cached feed and build new only if is not
    if (!$feed->isCached())
    {
        $adminUser = \App\Helpers\Functions\Users::adminUser();
        $paymentProcessor = \App\Models\PaymentProcessor::where('deleted_at', '=', null)
            ->orderBy('created_at', 'desc')
            ->get();

        // set your feed's title, description, link, pubdate and language
        $feed->title = 'Payment Processors Feed';
        $feed->description = 'This feed enables you to be updated when new processors are added to the site.';
        $feed->logo = env('APP_URL') . '/assets/images/og/bitcoin.png';
        $feed->link = url('payment-processors-feed');
        $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $feed->pubdate = $paymentProcessor[0]->created_at;
        $feed->lang = 'en';
        $feed->setShortening(true); // true or false
        $feed->setTextLimit(160); // maximum length of description text

        foreach ($paymentProcessor as $p)
        {
            // set item's title, author, url, pubdate, description, content, enclosure (optional)*
            $feed->add(
                $p->name,
                $adminUser->fullName(),
                route('payment-processors.show', ['slug' => $p->slug]),
                $p->created_at,
                $p->meta_title,
                $p->meta_description
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