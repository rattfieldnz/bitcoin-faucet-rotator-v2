<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Class Kernel
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Cors::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Fideloper\Proxy\TrustProxies::class,
        \App\Libraries\Matthewbdaly\ETagMiddleware\ETag::class,
        \Bepsvpt\SecureHeaders\SecureHeadersMiddleware::class,
        \HTMLMin\HTMLMin\Http\Middleware\MinifyMiddleware::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \DougSisk\BlockReferralSpam\Middleware\BlockReferralSpam::class,
            \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
            //\GrahamCampbell\HTMLMin\Http\Middleware\MinifyMiddleware::class
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'role' => \Laratrust\Middleware\LaratrustRole::class,
        'permission' => \Laratrust\Middleware\LaratrustPermission::class,
        'ability' => \Laratrust\Middleware\LaratrustAbility::class,
        'create_fresh_api_token' => \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
    ];
}
