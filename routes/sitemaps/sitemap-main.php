<?php

use App\Models\Alert;
use App\Models\Faucet;
use App\Models\PaymentProcessor;
use App\Models\PrivacyPolicy;
use App\Models\TermsAndConditions;
use App\Models\User;
use Carbon\Carbon;

Route::get('sitemap-main', function() {
    $sitemap = App::make('sitemap');

    if (!$sitemap->isCached()) {

        $currentTime = Carbon::now()->toW3cString();
        $sitemap->add(route('home'), $currentTime, '1.0', 'daily');

        $lastModifiedUser = User::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $sitemap->add(route('users.index'), $lastModifiedUser->updated_at->toW3cString(), '1.0', 'daily');

        $lastModifiedFaucet = Faucet::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $sitemap->add(route('faucets.index'), $lastModifiedFaucet->updated_at->toW3cString(), '1.0', 'daily');

        $lastModifiedPaymentProcessor = PaymentProcessor::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $sitemap->add(route('payment-processors.index'), $lastModifiedPaymentProcessor->updated_at->toW3cString(), '1.0', 'daily');

        $privacyPolicy = PrivacyPolicy::first();
        if(!empty($privacyPolicy)){
            $sitemap->add(
                route('privacy-policy'),
                $privacyPolicy->updated_at->toW3cString(),
                '1.0',
                'daily'
            );
        }

        $termsAndConditions = TermsAndConditions::first();
        if(!empty($termsAndConditions)){
            $sitemap->add(
                route('terms-and-conditions'),
                $termsAndConditions->updated_at->toW3cString(),
                '1.0',
                'daily'
            );
        }

        $alert = Alert::where('deleted_at', '=', null)
            ->orderBy('updated_at', 'desc')
            ->get()->first();
        $alertDate = !empty($alert) ? $alert->updated_at->toW3cString() : Carbon::now()->toW3cString();
        $sitemap->add(route('alerts.index'), $alertDate, '1.0', 'daily');
    }

    return $sitemap->render('xml');
});