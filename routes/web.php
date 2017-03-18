<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', 'HomeController@index');

/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'api', 'namespace' => 'API'], function () {
    Route::group(['prefix' => 'v1'], function () {
        require config('infyom.laravel_generator.path.api_routes');
    });
});


Route::get('/home', 'HomeController@index');

Route::resource('faucets', 'FaucetController');

Route::resource('payment-processors', 'PaymentProcessorController');

Route::resource('users', 'UserController');

Route::resource('main-meta', 'MainMetaController');

Route::resource('ad-block', 'AdBlockController');

Route::resource('twitter-config', 'TwitterConfigController');