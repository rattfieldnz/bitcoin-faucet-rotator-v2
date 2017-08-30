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

    Route::get('payment_processors', ['as' => 'payment_processors', 'uses' => 'PaymentProcessorAPIController@index']);

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
