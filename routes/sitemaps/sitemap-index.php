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
    $alertLazyLoad = \App\Models\Alert::orderBy('updated_at', 'desc');
    $alert = !empty($alertLazyLoad) ? $alertLazyLoad->first() : null;

    $userDate = !empty($user) ? $user->updated_at->toW3cString() : \Carbon\Carbon::now()->toW3cString();
    $sitemap->addSitemap(URL::to('sitemap-users'), $userDate);

    $faucetDate = !empty($faucet) ? $faucet->updated_at->toW3cString() : \Carbon\Carbon::now()->toW3cString();
    $sitemap->addSitemap(URL::to('sitemap-faucets'), $faucetDate);

    $ppDate = !empty($paymentProcessor) ? $paymentProcessor->updated_at->toW3cString() : \Carbon\Carbon::now()->toW3cString();
    $sitemap->addSitemap(URL::to('sitemap-payment-processors'), $ppDate);

    $sitemap->addSitemap(URL::to('sitemap-users-faucets'), $userDate);

    $sitemap->addSitemap(URL::to('sitemap-users-rotators'), $userDate);

    $ppFaucetDate = !empty($lastUserPaymentProcessorFaucet) ? $lastUserPaymentProcessorFaucet->updated_at->toW3cString() : \Carbon\Carbon::now()->toW3cString();
    $sitemap->addSitemap(URL::to('sitemap-users-payment-processors'), $ppFaucetDate);

    $alertDate = !empty($alert) ? $alert->updated_at->toW3cString() : \Carbon\Carbon::now()->toW3cString();
    $sitemap->addSitemap(URL::to('sitemap-alerts'), $alertDate);

    // show sitemap
    return $sitemap->render('sitemapindex');
});