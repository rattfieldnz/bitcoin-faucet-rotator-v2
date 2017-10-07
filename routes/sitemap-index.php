<?php

Route::get('sitemap', function()
{
    // create sitemap
    $sitemap = App::make("sitemap");

    // add sitemaps (loc, lastmod (optional))
    $sitemap->addSitemap(URL::to('sitemap-main'));

    $user = \App\Models\User::orderBy('updated_at', 'desc')->first();
    $sitemap->addSitemap(URL::to('sitemap-users'), $user->updated_at->toW3cString());

    $faucet = \App\Models\Faucet::orderBy('updated_at', 'desc')->first();
    $sitemap->addSitemap(URL::to('sitemap-faucets'), $faucet->updated_at->toW3cString());

    $paymentProcessor = \App\Models\PaymentProcessor::orderBy('updated_at', 'desc')->first();
    $sitemap->addSitemap(URL::to('sitemap-payment-processors'), $paymentProcessor->updated_at->toW3cString());

    // show sitemap
    return $sitemap->render('sitemapindex');
});