<?php

Route::get('sitemap-users-payment-processors', function() {
    $sitemap = App::make('sitemap');

    $users = \App\Models\User::all();
    $paymentProcessors = \App\Models\PaymentProcessor::all();

    if (!$sitemap->isCached()) {

        foreach($users as $u){

            $paymentProcessorList = route('users.payment-processors', ['userSlug' => $u->slug]);
            $sitemap->add($paymentProcessorList, \Carbon\Carbon::now()->toW3cString(), '1.0', 'daily');

            foreach($paymentProcessors as $p){

                $lastFaucet = \App\Helpers\Functions\PaymentProcessors::userPaymentProcessorFaucets($u, $p)
                    ->sortByDesc('updated_at')
                    ->first();

                $faucetsList = route(
                    'users.payment-processors.faucets',
                    [
                        'userSlug' => $u->slug,
                        'paymentProcessorSlug' => $p->slug
                    ]
                );

                $sitemap->add($faucetsList, $lastFaucet->updated_at->toW3cString(), '1.0', 'daily');

                $rotatorList = route('users.payment-processors.rotator',
                    [
                        'userSlug' => $u->slug,
                        'paymentProcessorSlug' => $p->slug
                    ]
                );
                $sitemap->add($rotatorList, $lastFaucet->updated_at->toW3cString(), '1.0', 'daily');
            }
        }

    }

    return $sitemap->render('xml');
});