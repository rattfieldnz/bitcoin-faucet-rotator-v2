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
});

