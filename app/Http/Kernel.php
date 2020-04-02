<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Cors;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\VerifyCsrfToken;
use App\Libraries\Matthewbdaly\ETagMiddleware\ETag;
use Bepsvpt\SecureHeaders\SecureHeadersMiddleware;
use DougSisk\BlockReferralSpam\Middleware\BlockReferralSpam;
use Fideloper\Proxy\TrustProxies;
use HTMLMin\HTMLMin\Http\Middleware\MinifyMiddleware;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\Authorize;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laratrust\Middleware\LaratrustAbility;
use Laratrust\Middleware\LaratrustPermission;
use Laratrust\Middleware\LaratrustRole;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;

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
        CheckForMaintenanceMode::class,
        EncryptCookies::class,
        AddQueuedCookiesToResponse::class,
        StartSession::class,
        ShareErrorsFromSession::class,
        Cors::class,
        VerifyCsrfToken::class,
        TrustProxies::class,
        ETag::class,
        SecureHeadersMiddleware::class,
        MinifyMiddleware::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            BlockReferralSpam::class,
            CreateFreshApiToken::class,
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
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'throttle' => ThrottleRequests::class,
        'role' => LaratrustRole::class,
        'permission' => LaratrustPermission::class,
        'ability' => LaratrustAbility::class,
        'create_fresh_api_token' => CreateFreshApiToken::class,
    ];
}
