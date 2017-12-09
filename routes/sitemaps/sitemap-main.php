<?php

Route::get('sitemap-main', function() {
    $sitemap = App::make('sitemap');

    if (!$sitemap->isCached()) {

        $currentTime = \Carbon\Carbon::now()->toW3cString();
        $sitemap->add(route('home'), $currentTime, '1.0', 'daily');

        $lastModifiedUser = \App\Models\User::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $sitemap->add(route('users.index'), $lastModifiedUser->updated_at->toW3cString(), '1.0', 'daily');

        $lastModifiedFaucet = \App\Models\Faucet::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $sitemap->add(route('faucets.index'), $lastModifiedFaucet->updated_at->toW3cString(), '1.0', 'daily');

        $lastModifiedPaymentProcessor = \App\Models\PaymentProcessor::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $sitemap->add(route('payment-processors.index'), $lastModifiedPaymentProcessor->updated_at->toW3cString(), '1.0', 'daily');

        $privacyPolicy = \App\Models\PrivacyPolicy::first();
        if(!empty($privacyPolicy)){
            $sitemap->add(
                route('privacy-policy'),
                $privacyPolicy->updated_at->toW3cString(),
                '1.0',
                'daily'
            );
        }

        $termsAndConditions = \App\Models\TermsAndConditions::first();
        if(!empty($termsAndConditions)){
            $sitemap->add(
                route('terms-and-conditions'),
                $termsAndConditions->updated_at->toW3cString(),
                '1.0',
                'daily'
            );
        }

        $alert = \App\Models\Alert::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $alertDate = !empty($alert) ? $alert->updated_at->toW3cString() : \Carbon\Carbon::now()->toW3cString();
        $sitemap->add(route('alerts.index'), $alertDate, '1.0', 'daily');
    }

    return $sitemap->render('xml');
});