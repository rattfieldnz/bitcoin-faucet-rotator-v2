<?php

Route::get('sitemap', function()
{
    // create sitemap
    $sitemap = App::make("sitemap");

    // add sitemaps (loc, lastmod (optional))
    $sitemap->addSitemap(URL::to('sitemap-main'));

    $user = \App\Models\User::orderBy('updated_at', 'desc')->first();
    $paymentProcessor = \App\Models\PaymentProcessor::orderBy('updated_at', 'desc')->first();
    $faucet = \App\Models\Faucet::orderBy('updated_at', 'desc')->first();
    $lastUserPaymentProcessorFaucet = \App\Helpers\Functions\PaymentProcessors::userPaymentProcessorFaucets($user, $paymentProcessor)
        ->sortByDesc('updated_at')
        ->first();
    $alert = \App\Models\Alert::orderBy('updated_at', 'desc')->first();

    $sitemap->addSitemap(URL::to('sitemap-users'), $user->updated_at->toW3cString());

    $sitemap->addSitemap(URL::to('sitemap-faucets'), $faucet->updated_at->toW3cString());

    $sitemap->addSitemap(URL::to('sitemap-payment-processors'), $paymentProcessor->updated_at->toW3cString());

    $sitemap->addSitemap(URL::to('sitemap-users-faucets'), $user->updated_at->toW3cString());

    $sitemap->addSitemap(URL::to('sitemap-users-rotators'), $user->updated_at->toW3cString());

    $sitemap->addSitemap(URL::to('sitemap-users-payment-processors'), $lastUserPaymentProcessorFaucet->updated_at->toW3cString());

    $sitemap->addSitemap(URL::to('sitemap-alerts'), $alert->updated_at->toW3cString());

    // show sitemap
    return $sitemap->render('sitemapindex');
});