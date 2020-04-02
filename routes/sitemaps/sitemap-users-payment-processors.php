<?php

use App\Helpers\Functions\PaymentProcessors;
use App\Models\PaymentProcessor;
use App\Models\User;
use Carbon\Carbon;

Route::get('sitemap-users-payment-processors', function() {
    $sitemap = App::make('sitemap');

    $users = User::all();
    $paymentProcessors = PaymentProcessor::all();

    if (!$sitemap->isCached()) {

        foreach($users as $u){

            $paymentProcessorList = route('users.payment-processors', ['userSlug' => $u->slug]);
            $sitemap->add($paymentProcessorList, Carbon::now()->toW3cString(), '1.0', 'daily');

            foreach($paymentProcessors as $p){

                $lastFaucet = PaymentProcessors::userPaymentProcessorFaucets($u, $p)
                    ->sortByDesc('updated_at')
                    ->first();

                if(!empty($lastFaucet)){
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

    }

    return $sitemap->render('xml');
});