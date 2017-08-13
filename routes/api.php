<?php


/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'v1', 'namespace' => 'API'], function () {

    Route::get('faucets', ['as' => 'faucets', 'uses' => 'FaucetAPIController@index']);
    Route::get('faucets/{slug}', ['as' => 'faucets.show', 'uses' => 'FaucetAPIController@show']);
    Route::get('payment_processors', ['as' => 'payment_processors', 'uses' => 'PaymentProcessorAPIController@index']);

    Route::get(
        'top-pages/from/{dateFrom}/to/{dateTo}/quantity/{quantity?}',
        [
            'as' => 'stats.top-pages-between-dates',
            'uses' => 'StatsAPIController@getPagesVisited'
        ]
    );

    Route::get('visits-and-page-views/from/{dateFrom}/to/{dateTo}/quantity/{quantity?}',
        [
            'as' => 'stats.visits-and-page-views',
            'uses' => 'StatsAPIController@getVisitorsAndPageViews'
        ]);
});

