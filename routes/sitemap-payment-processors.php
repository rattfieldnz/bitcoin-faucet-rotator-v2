<?php

Route::get('sitemap-payment-processors', function() {
    $sitemap = App::make('sitemap');

    if (!$sitemap->isCached()) {

        $paymentProcessors = \App\Models\PaymentProcessor::where('deleted_at', '=', null)->get();

        foreach($paymentProcessors as $p){
            $url = route('payment-processors.show', ['slug' => $p->slug]);
            $sitemap->add($url, $p->updated_at->toW3cString(), '1.0', 'daily');
        }
    }

    return $sitemap->render('xml');
});