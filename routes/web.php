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

header('Access-Control-Allow-Origin: ' . env('APP_URL'));
header('Access-Control-Allow-Credentials: true');

Route::auth();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/', ['as' => 'home', 'uses' => 'RotatorController@index']);

Route::delete(
    'faucets/{slug}/delete-permanently',
    [
        'as' => 'faucets.delete-permanently',
        'uses' => 'FaucetController@destroyPermanently'
    ]
);

Route::patch(
    'faucets/{slug}/restore',
    [
        'as' => 'faucets.restore',
        'uses' => 'FaucetController@restoreDeleted'
    ]
);

Route::get('faucets/create', [
    'as' => 'faucets.create',
    'uses' => 'FaucetController@create'
]);

Route::get('faucets/{slug}/edit', [
    'as' => 'faucets.edit',
    'uses' => 'FaucetController@edit'
]);

Route::get('faucets/{slug}', [
    'as' => 'faucet.show',
    'uses' => 'FaucetController@show'
]);

Route::get('users/{userSlug}/faucets', ['as' => 'users.faucets', 'uses' => 'UserFaucetsController@index']);
Route::post('users/{userSlug}/faucets/store', ['as' => 'users.faucets.store', 'uses' => 'UserFaucetsController@store']);
Route::get('users/{userSlug}/faucets/{faucetSlug}', ['as' => 'users.faucets.show', 'uses' => 'UserFaucetsController@show']);

Route::get('users/{userSlug}/payment-processors', ['as' => 'users.payment-processors', 'uses' => 'PaymentProcessorController@userPaymentProcessors']);
Route::get('users/{userSlug}/payment-processors/{paymentProcessorSlug}', function ($userSlug, $paymentProcessorSlug) {
    return redirect(route('users.payment-processors.faucets', ['userSlug' =>  $userSlug, 'paymentProcessorSlug' => $paymentProcessorSlug]));
});

Route::get('users/{userSlug}/payment-processors/{paymentProcessorSlug}/faucets',
    [
        'as' => 'users.payment-processors.faucets',
        'uses' => 'PaymentProcessorController@userPaymentProcessorFaucets'
    ]
);

Route::get('users/{userSlug}/payment-processors/{paymentProcessorSlug}/rotator',
    [
        'as' => 'users.payment-processors.rotator',
        'uses' => 'RotatorController@getUserPaymentProcessorFaucetRotator'
    ]
);

Route::get('users/{userSlug}/faucets/{faucetSlug}/edit', function ($userSlug) {
    return redirect(route('users.faucets', ['userSlug' =>  $userSlug]));
});
Route::patch('users/{userSlug}/faucets/update-multiple', ['as' => 'users.faucets.update-multiple', 'uses' => 'UserFaucetsController@updateMultiple']);

Route::get('users/{userSlug}/rotator', [
    'as' => 'users.rotator',
    'uses' => 'RotatorController@getUserFaucetRotator'
]);

Route::resource('faucets', 'FaucetController');

Route::delete(
    'payment-processors/{slug}/delete-permanently',
    [
        'as' => 'payment-processors.delete-permanently',
        'uses' => 'PaymentProcessorController@destroyPermanently'
    ]
);

Route::patch(
    'payment-processors/{slug}/restore',
    [
        'as' => 'payment-processors.restore',
        'uses' => 'PaymentProcessorController@restoreDeleted'
    ]
);

Route::get(
    'payment-processors/{slug}/faucets',
    [
        'as' => 'payment-processors.faucets',
        'uses' => 'PaymentProcessorController@faucets'
    ]
);

Route::get('payment-processors/{slug}/rotator',
    [
        'as' => 'payment-processors.rotator',
        'uses' => 'RotatorController@getPaymentProcessorFaucetRotator'
    ]
);

Route::resource('payment-processors', 'PaymentProcessorController');

Route::delete(
    'users/{slug}/delete-permanently',
    [
            'as' => 'users.delete-permanently',
            'uses' => 'UserController@destroyPermanently'
        ]
);

Route::delete(
    'purge-deleted-users',
    [
        'as' => 'users.purge-archived',
        'uses' => 'UserController@purgeArchivedUsers'
    ]
);

Route::patch(
    'users/{slug}/restore',
    [
        'as' => 'users.restore',
        'uses' => 'UserController@restoreDeleted'
    ]
);

Route::resource('users', 'UserController');

Route::resource('main-meta', 'MainMetaController');

Route::resource('ad-block', 'AdBlockController');

Route::resource('twitter-config', 'TwitterConfigController');

Route::resource('roles', 'RoleController');

Route::resource('permissions', 'PermissionController');

Route::get('privacy-policy/edit', ['as' => 'privacy-policy.edit', 'uses' => 'PrivacyPolicyController@edit']);

Route::resource('privacy-policy', 'PrivacyPolicyController');

Route::get('privacy-policy', ['as' => 'privacy-policy', 'uses' => 'PrivacyPolicyController@index']);

Route::get('terms-and-conditions/edit', ['as' => 'terms-and-conditions.edit', 'uses' => 'TermsAndConditionsController@edit']);

Route::resource('terms-and-conditions', 'TermsAndConditionsController');

Route::get('terms-and-conditions', ['as' => 'terms-and-conditions', 'uses' => 'TermsAndConditionsController@show']);

Route::get('stats', ['as' => 'stats.index', 'uses' => 'StatsController@index']);

Route::get('settings', ['as' => 'settings', 'uses' => 'SettingsController@index']);

Route::resource('social-networks', 'SocialNetworksController');

Route::delete(
    'alerts/{slug}/delete-temporarily',
    [
        'as' => 'alerts.delete-temporarily',
        'uses' => 'AlertController@destroy'
    ]
);

Route::delete(
    'alerts/{slug}/delete-permanently',
    [
        'as' => 'alerts.delete-permanently',
        'uses' => 'AlertController@destroyPermanently'
    ]
);

Route::patch(
    'alerts/{slug}/restore',
    [
        'as' => 'alerts.restore',
        'uses' => 'AlertController@restoreDeleted'
    ]
);

Route::resource('alerts', 'AlertController');

Route::resource('alert-types', 'AlertTypeController');