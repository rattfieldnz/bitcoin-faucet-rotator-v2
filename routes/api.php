<?php

/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/


// CORS
header('Access-Control-Allow-Origin: ' . env('APP_URL'));
header('Access-Control-Allow-Credentials: true');

Route::group(['prefix' => 'v1', 'namespace' => 'API'], function () {

    Route::get('faucets', ['as' => 'faucets', 'uses' => 'FaucetAPIController@index']);
    Route::get('faucets/{slug}', ['as' => 'faucets.show', 'uses' => 'FaucetAPIController@show']);
    Route::get('first-faucet', ['as' => 'faucets.first-faucet', 'uses' => 'FaucetAPIController@getFirstFaucet']);
    Route::get('faucets/{slug}/previous-faucet', ['as' => 'faucets.previous-faucet', 'uses' => 'FaucetAPIController@getPreviousFaucet']);
    Route::get('faucets/{slug}/next-faucet', ['as' => 'faucets.next-faucet', 'uses' => 'FaucetAPIController@getNextFaucet']);
    Route::get('last-faucet', ['as' => 'faucets.last-faucet', 'uses' => 'FaucetAPIController@getLastFaucet']);
    Route::get('random-faucet', ['as' => 'faucets.random-faucet', 'uses' => 'FaucetAPIController@getRandomFaucet']);

    Route::get('payment-processors', ['as' => 'payment-processors', 'uses' => 'PaymentProcessorAPIController@index']);

    Route::get('payment-processors/{slug}', ['as' => 'payment-processors.show', 'uses' => 'PaymentProcessorAPIController@show']);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets/{faucetSlug}', [
        'as' => 'payment-processor.faucet',
        'uses' => 'FaucetAPIController@getPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets', [
        'as' => 'payment-processor.faucets',
        'uses' => 'FaucetAPIController@getPaymentProcessorFaucets'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/first-faucet', [
        'as' => 'payment-processor.first-faucet',
        'uses' => 'FaucetAPIController@getFirstPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets/{faucetSlug}/previous-faucet', [
        'as' => 'payment-processor.previous-faucet',
        'uses' => 'FaucetAPIController@getPreviousPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets/{faucetSlug}/next-faucet', [
        'as' => 'payment-processor.next-faucet',
        'uses' => 'FaucetAPIController@getNextPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/last-faucet', [
        'as' => 'payment-processor.last-faucet',
        'uses' => 'FaucetAPIController@getLastPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/random-faucet', [
        'as' => 'payment-processor.random-faucet',
        'uses' => 'FaucetAPIController@getRandomPaymentProcessorFaucet'
    ]);

    Route::get(
        'top-pages/from/{dateFrom}/to/{dateTo}/quantity/{quantity?}',
        [
            'as' => 'stats.top-pages-between-dates',
            'uses' => 'StatsAPIController@getPagesVisited'
        ]
    );

    Route::get(
        'visits-and-page-views/from/{dateFrom}/to/{dateTo}/quantity/{quantity?}',
        [
            'as' => 'stats.visits-and-page-views',
            'uses' => 'StatsAPIController@getVisitorsAndPageViews'
        ]
    );

    Route::get(
        'top-x-browsers/from/{dateFrom}/to/{dateTo}/max-browsers/{maxBrowsers?}',
        [
            'as' => 'stats.top-x-browsers',
            'uses' => 'StatsAPIController@getTopBrowsersAndVisitors'
        ]
    );

    Route::get(
        'countries-and-visitors/from/{dateFrom}/to/{dateTo}',
        [
            'as' => 'stats.countries-and-visitors',
            'uses' => 'StatsAPIController@getCountriesAndVisitors'
        ]
    );
});
