<?php

Route::get('sitemap-payment-processors', function() {
    $sitemap = App::make('sitemap');

    if (!$sitemap->isCached()) {

        $paymentProcessors = \App\Models\PaymentProcessor::all();

        foreach($paymentProcessors as $p){
            $url = route('payment-processors.show', ['slug' => $p->slug]);
            $sitemap->add($url, $p->updated_at->toW3cString(), '1.0', 'daily');

            $faucetsList = route('payment-processors.faucets', ['slug' => $p->slug]);
            $sitemap->add($faucetsList, $p->updated_at->toW3cString(), '1.0', 'daily');

            $rotatorList = route('payment-processors.rotator', ['slug' => $p->slug]);
            $sitemap->add($rotatorList, $p->updated_at->toW3cString(), '1.0', 'daily');
        }
    }

    return $sitemap->render('xml');
});