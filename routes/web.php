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

Route::resource('faucets', 'FaucetController');
Route::get('faucets/{slug}', [
    'as' => 'faucets.show',
    'uses' => 'FaucetController@show'
]);
Route::get('faucets/{slug}/edit', [
    'as' => 'faucets.edit',
    'uses' => 'FaucetController@edit'
]);
Route::patch(
    'faucets/{slug}/update',
    [
        'as' => 'faucets.update',
        'uses' => 'FaucetController@update'
    ]
);
Route::delete(
    'faucets/{slug}/destroy',
    [
        'as' => 'faucets.destroy',
        'uses' => 'FaucetController@destroy'
    ]
);
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

Route::resource('payment-processors', 'PaymentProcessorController');
Route::get(
    'payment-processors/{slug}',
    [
        'as' => 'payment-processors.show',
        'uses' => 'PaymentProcessorController@show'
    ]
);
Route::get(
    'payment-processors/{slug}/edit',
    [
        'as' => 'payment-processors.edit',
        'uses' => 'PaymentProcessorController@edit'
    ]
);
Route::patch(
    'payment-processors/{slug}/update',
    [
        'as' => 'payment-processors.update',
        'uses' => 'PaymentProcessorController@update'
    ]
);
Route::delete(
    'payment-processors/{slug}/destroy',
    [
        'as' => 'payment-processors.destroy',
        'uses' => 'PaymentProcessorController@destroy'
    ]
);
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
Route::get(
    'payment-processors/{slug}/rotator',
    [
        'as' => 'payment-processors.rotator',
        'uses' => 'RotatorController@getPaymentProcessorFaucetRotator'
    ]
);

Route::resource('users', 'UserController');
Route::get(
    'users/{slug}',
    [
        'as' => 'users.show',
        'uses' => 'UserController@show'
    ]
);
Route::get(
    'users/{slug}/edit',
    [
        'as' => 'users.edit',
        'uses' => 'UserController@edit'
    ]
);
Route::delete(
    'users/{slug}/destroy',
    [
        'as' => 'users.destroy',
        'uses' => 'UserController@destroy'
    ]
);
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
Route::get('users/{slug}/faucets', ['as' => 'users.faucets', 'uses' => 'UserFaucetsController@index']);
Route::post('users/{slug}/faucets/store', ['as' => 'users.faucets.store', 'uses' => 'UserFaucetsController@store']);
Route::get('users/{slug}/faucets/{faucetSlug}', ['as' => 'users.faucets.show', 'uses' => 'UserFaucetsController@show']);
Route::get('users/{slug}/payment-processors', ['as' => 'users.payment-processors', 'uses' => 'PaymentProcessorController@userPaymentProcessors']);
Route::get('users/{slug}/payment-processors/{paymentProcessorSlug}', function ($slug, $paymentProcessorSlug) {
    return redirect(route('users.payment-processors.faucets', ['slug' =>  $slug, 'paymentProcessorSlug' => $paymentProcessorSlug]));
});
Route::get(
    'users/{slug}/payment-processors/{paymentProcessorSlug}/faucets',
    [
        'as' => 'users.payment-processors.faucets',
        'uses' => 'PaymentProcessorController@userPaymentProcessorFaucets'
    ]
);
Route::get(
    'users/{slug}/payment-processors/{paymentProcessorSlug}/rotator',
    [
        'as' => 'users.payment-processors.rotator',
        'uses' => 'RotatorController@getUserPaymentProcessorFaucetRotator'
    ]
);
Route::get('users/{slug}/faucets/{faucetSlug}/edit', function ($slug) {
    return redirect(route('users.faucets', ['slug' =>  $slug]));
});
Route::patch('users/{slug}/faucets/update-multiple', ['as' => 'users.faucets.update-multiple', 'uses' => 'UserFaucetsController@updateMultiple']);
Route::get('users/{slug}/rotator', [
    'as' => 'users.rotator',
    'uses' => 'RotatorController@getUserFaucetRotator'
]);

Route::resource('main-meta', 'MainMetaController');


Route::resource('ad-block', 'AdBlockController');

Route::resource('twitter-config', 'TwitterConfigController');

Route::resource('roles', 'RoleController');
Route::get(
    'roles/{slug}',
    [
        'as' => 'roles.show',
        'uses' => 'RolesController@show'
    ]
);

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

Route::get('users.export-as-csv', 'UserController@exportCSV')
    ->name('users.export-as-csv');
Route::get('faucets.export-as-csv', 'FaucetController@exportCSV')
    ->name('faucets.export-as-csv');
Route::get('payment-processors.export-as-csv', 'PaymentProcessorController@exportCSV')
    ->name('payment-processors.export-as-csv');
Route::get('main-meta.export-as-csv', 'MainMetaController@exportCSV')
    ->name('main-meta.export-as-csv');
Route::get('ad-block.export-as-csv', 'AdBlockController@exportCSV')
    ->name('ad-block.export-as-csv');
Route::get('privacy-policy.export-as-csv', 'PrivacyPolicyController@exportCSV')
    ->name('privacy-policy.export-as-csv');
Route::get('terms-and-conditions.export-as-csv', 'TermsAndConditionsController@exportCSV')
    ->name('terms-and-conditions.export-as-csv');
