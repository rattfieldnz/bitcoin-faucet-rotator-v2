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
    Route::get('faucets/{id}', ['as' => 'faucets.show', 'uses' => 'FaucetAPIController@show']);
    Route::get('first_faucet', ['as' => 'faucets.first', 'uses' => 'FaucetAPIController@getFirstFaucet']);
    Route::get('faucets/{id}/previous', ['as' => 'faucets.previous', 'uses' => 'FaucetAPIController@getPreviousFaucet']);
    Route::get('faucets/{id}/next', ['as' => 'faucets.next', 'uses' => 'FaucetAPIController@getNextFaucet']);
    Route::get('last_faucet', ['as' => 'faucets.last', 'uses' => 'FaucetAPIController@getLastFaucet']);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets/{faucetSlug}', [
        'as' => 'payment-processor.faucet',
        'uses' => 'FaucetAPIController@paymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets', [
        'as' => 'payment-processor.faucets',
        'uses' => 'FaucetAPIController@paymentProcessorFaucets'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/first-faucet', [
        'as' => 'payment-processor.first-faucet',
        'uses' => 'FaucetAPIController@firstPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets/{faucetSlug}/previous', [
        'as' => 'payment-processor.previous-faucet',
        'uses' => 'FaucetAPIController@previousPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/faucets/{faucetSlug}/next', [
        'as' => 'payment-processor.next-faucet',
        'uses' => 'FaucetAPIController@nextPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors/{paymentProcessorSlug}/last-faucet', [
        'as' => 'payment-processor.last-faucet',
        'uses' => 'FaucetAPIController@lastPaymentProcessorFaucet'
    ]);

    Route::get('payment-processors', ['as' => 'payment-processors', 'uses' => 'PaymentProcessorAPIController@index']);

    Route::get('payment-processors/{slug}', ['as' => 'payment-processors.show', 'uses' => 'PaymentProcessorAPIController@show']);

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
