<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group(
            [
            'middleware' => 'web',
            'namespace' => $this->namespace,
            ],
            function ($router) {
                include base_path('routes/web.php');

                include base_path('routes/sitemap-main.php');
                include base_path('routes/sitemap-index.php');

                include base_path('routes/sitemap-users.php');
                include base_path('routes/sitemap-users-faucets.php');
                include base_path('routes/sitemap-users-rotators.php');
                include base_path('routes/sitemap-users-payment-processors.php');

                include base_path('routes/sitemap-faucets.php');
                include base_path('routes/sitemap-payment-processors.php');

                include base_path('routes/feeds/faucets-feed.php');
            }
        );
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group(
            [
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
            ],
            function ($router) {
                include base_path('routes/api.php');
            }
        );
    }
}
