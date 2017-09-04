(function () {

    var laroute = (function () {

        var routes = {

            absolute: false,
            rootUrl: 'http://freebtc.website.new.localhost:8080',
            routes : [{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/authorize","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizationController@authorize"},{"host":null,"methods":["POST"],"uri":"oauth\/authorize","name":null,"action":"\Laravel\Passport\Http\Controllers\ApproveAuthorizationController@approve"},{"host":null,"methods":["DELETE"],"uri":"oauth\/authorize","name":null,"action":"\Laravel\Passport\Http\Controllers\DenyAuthorizationController@deny"},{"host":null,"methods":["POST"],"uri":"oauth\/token","name":null,"action":"\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/tokens","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser"},{"host":null,"methods":["DELETE"],"uri":"oauth\/tokens\/{token_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy"},{"host":null,"methods":["POST"],"uri":"oauth\/token\/refresh","name":null,"action":"\Laravel\Passport\Http\Controllers\TransientTokenController@refresh"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/clients","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@forUser"},{"host":null,"methods":["POST"],"uri":"oauth\/clients","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@store"},{"host":null,"methods":["PUT"],"uri":"oauth\/clients\/{client_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@update"},{"host":null,"methods":["DELETE"],"uri":"oauth\/clients\/{client_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\ClientController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/scopes","name":null,"action":"\Laravel\Passport\Http\Controllers\ScopeController@all"},{"host":null,"methods":["GET","HEAD"],"uri":"oauth\/personal-access-tokens","name":null,"action":"\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser"},{"host":null,"methods":["POST"],"uri":"oauth\/personal-access-tokens","name":null,"action":"\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store"},{"host":null,"methods":["DELETE"],"uri":"oauth\/personal-access-tokens\/{token_id}","name":null,"action":"\Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/faucets","name":"faucets","action":"App\Http\Controllers\API\FaucetAPIController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/faucets\/{slug}","name":"faucets.show","action":"App\Http\Controllers\API\FaucetAPIController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/first-faucet","name":"faucets.first-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getFirstFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/faucets\/{slug}\/previous-faucet","name":"faucets.previous-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getPreviousFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/faucets\/{slug}\/next-faucet","name":"faucets.next-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getNextFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/last-faucet","name":"faucets.last-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getLastFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/random-faucet","name":"faucets.random-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getRandomFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors","name":"payment-processors","action":"App\Http\Controllers\API\PaymentProcessorAPIController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{slug}","name":"payment-processors.show","action":"App\Http\Controllers\API\PaymentProcessorAPIController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{paymentProcessorSlug}\/faucets\/{faucetSlug}","name":"payment-processor.faucet","action":"App\Http\Controllers\API\FaucetAPIController@getPaymentProcessorFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{paymentProcessorSlug}\/faucets","name":"payment-processor.faucets","action":"App\Http\Controllers\API\FaucetAPIController@getPaymentProcessorFaucets"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{paymentProcessorSlug}\/first-faucet","name":"payment-processor.first-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getFirstPaymentProcessorFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{paymentProcessorSlug}\/faucets\/{faucetSlug}\/previous-faucet","name":"payment-processor.previous-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getPreviousPaymentProcessorFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{paymentProcessorSlug}\/faucets\/{faucetSlug}\/next-faucet","name":"payment-processor.next-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getNextPaymentProcessorFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{paymentProcessorSlug}\/last-faucet","name":"payment-processor.last-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getLastPaymentProcessorFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/payment-processors\/{paymentProcessorSlug}\/random-faucet","name":"payment-processor.random-faucet","action":"App\Http\Controllers\API\FaucetAPIController@getRandomPaymentProcessorFaucet"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/top-pages\/from\/{dateFrom}\/to\/{dateTo}\/quantity\/{quantity?}","name":"stats.top-pages-between-dates","action":"App\Http\Controllers\API\StatsAPIController@getPagesVisited"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/visits-and-page-views\/from\/{dateFrom}\/to\/{dateTo}\/quantity\/{quantity?}","name":"stats.visits-and-page-views","action":"App\Http\Controllers\API\StatsAPIController@getVisitorsAndPageViews"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/top-x-browsers\/from\/{dateFrom}\/to\/{dateTo}\/max-browsers\/{maxBrowsers?}","name":"stats.top-x-browsers","action":"App\Http\Controllers\API\StatsAPIController@getTopBrowsersAndVisitors"},{"host":null,"methods":["GET","HEAD"],"uri":"api\/v1\/countries-and-visitors\/from\/{dateFrom}\/to\/{dateTo}","name":"stats.countries-and-visitors","action":"App\Http\Controllers\API\StatsAPIController@getCountriesAndVisitors"},{"host":null,"methods":["GET","HEAD"],"uri":"login","name":"login","action":"App\Http\Controllers\Auth\LoginController@showLoginForm"},{"host":null,"methods":["POST"],"uri":"login","name":null,"action":"App\Http\Controllers\Auth\LoginController@login"},{"host":null,"methods":["POST"],"uri":"logout","name":"logout","action":"App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"register","name":"register","action":"App\Http\Controllers\Auth\RegisterController@showRegistrationForm"},{"host":null,"methods":["POST"],"uri":"register","name":null,"action":"App\Http\Controllers\Auth\RegisterController@register"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset","name":"password.request","action":"App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm"},{"host":null,"methods":["POST"],"uri":"password\/email","name":"password.email","action":"App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail"},{"host":null,"methods":["GET","HEAD"],"uri":"password\/reset\/{token}","name":"password.reset","action":"App\Http\Controllers\Auth\ResetPasswordController@showResetForm"},{"host":null,"methods":["POST"],"uri":"password\/reset","name":null,"action":"App\Http\Controllers\Auth\ResetPasswordController@reset"},{"host":null,"methods":["GET","HEAD"],"uri":"logout","name":null,"action":"\App\Http\Controllers\Auth\LoginController@logout"},{"host":null,"methods":["GET","HEAD"],"uri":"\/","name":"home","action":"App\Http\Controllers\RotatorController@index"},{"host":null,"methods":["DELETE"],"uri":"faucets\/{slug}\/delete-permanently","name":"faucets.delete-permanently","action":"App\Http\Controllers\FaucetController@destroyPermanently"},{"host":null,"methods":["PATCH"],"uri":"faucets\/{slug}\/restore","name":"faucets.restore","action":"App\Http\Controllers\FaucetController@restoreDeleted"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/faucets","name":"users.faucets","action":"App\Http\Controllers\UserFaucetsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/faucets\/create","name":"users.faucets.create","action":"App\Http\Controllers\UserFaucetsController@create"},{"host":null,"methods":["POST"],"uri":"users\/{userSlug}\/faucets\/store","name":"users.faucets.store","action":"App\Http\Controllers\UserFaucetsController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/faucets\/{faucetSlug}","name":"users.faucets.show","action":"App\Http\Controllers\UserFaucetsController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/payment-processors","name":"users.payment-processors","action":"App\Http\Controllers\PaymentProcessorController@userPaymentProcessors"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/payment-processors\/{paymentProcessorSlug}","name":null,"action":"Closure"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/payment-processors\/{paymentProcessorSlug}\/faucets","name":"users.payment-processors.faucets","action":"App\Http\Controllers\PaymentProcessorController@userPaymentProcessorFaucets"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/faucets\/{faucetSlug}\/edit","name":null,"action":"Closure"},{"host":null,"methods":["PATCH"],"uri":"users\/{userSlug}\/faucets\/{faucetSlug}\/update","name":"users.faucets.update","action":"App\Http\Controllers\UserFaucetsController@update"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/faucets\/{faucetSlug}\/destroy","name":"users.faucets.destroy","action":"App\Http\Controllers\UserFaucetsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/faucets\/{faucetSlug}\/delete-permanently","name":"users.faucets.delete-permanently","action":"App\Http\Controllers\UserFaucetsController@destroyPermanently"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{userSlug}\/faucets\/{faucetSlug}\/restore","name":"users.faucets.restore","action":"App\Http\Controllers\UserFaucetsController@restoreDeleted"},{"host":null,"methods":["PATCH"],"uri":"users\/{userSlug}\/faucets\/update-multiple","name":"users.faucets.update-multiple","action":"App\Http\Controllers\UserFaucetsController@updateMultiple"},{"host":null,"methods":["GET","HEAD"],"uri":"faucets","name":"faucets.index","action":"App\Http\Controllers\FaucetController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"faucets\/create","name":"faucets.create","action":"App\Http\Controllers\FaucetController@create"},{"host":null,"methods":["POST"],"uri":"faucets","name":"faucets.store","action":"App\Http\Controllers\FaucetController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"faucets\/{faucet}","name":"faucets.show","action":"App\Http\Controllers\FaucetController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"faucets\/{faucet}\/edit","name":"faucets.edit","action":"App\Http\Controllers\FaucetController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"faucets\/{faucet}","name":"faucets.update","action":"App\Http\Controllers\FaucetController@update"},{"host":null,"methods":["DELETE"],"uri":"faucets\/{faucet}","name":"faucets.destroy","action":"App\Http\Controllers\FaucetController@destroy"},{"host":null,"methods":["DELETE"],"uri":"payment-processors\/{slug}\/delete-permanently","name":"payment-processors.delete-permanently","action":"App\Http\Controllers\PaymentProcessorController@destroyPermanently"},{"host":null,"methods":["PATCH"],"uri":"payment-processors\/{slug}\/restore","name":"payment-processors.restore","action":"App\Http\Controllers\PaymentProcessorController@restoreDeleted"},{"host":null,"methods":["GET","HEAD"],"uri":"payment-processors\/{slug}\/faucets","name":"payment-processors.faucets","action":"App\Http\Controllers\PaymentProcessorController@faucets"},{"host":null,"methods":["GET","HEAD"],"uri":"payment-processors\/{slug}\/rotator","name":"payment-processors.rotator","action":"App\Http\Controllers\RotatorController@getPaymentProcessorFaucetRotator"},{"host":null,"methods":["GET","HEAD"],"uri":"payment-processors","name":"payment-processors.index","action":"App\Http\Controllers\PaymentProcessorController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"payment-processors\/create","name":"payment-processors.create","action":"App\Http\Controllers\PaymentProcessorController@create"},{"host":null,"methods":["POST"],"uri":"payment-processors","name":"payment-processors.store","action":"App\Http\Controllers\PaymentProcessorController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"payment-processors\/{payment_processor}","name":"payment-processors.show","action":"App\Http\Controllers\PaymentProcessorController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"payment-processors\/{payment_processor}\/edit","name":"payment-processors.edit","action":"App\Http\Controllers\PaymentProcessorController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"payment-processors\/{payment_processor}","name":"payment-processors.update","action":"App\Http\Controllers\PaymentProcessorController@update"},{"host":null,"methods":["DELETE"],"uri":"payment-processors\/{payment_processor}","name":"payment-processors.destroy","action":"App\Http\Controllers\PaymentProcessorController@destroy"},{"host":null,"methods":["DELETE"],"uri":"users\/{slug}\/delete-permanently","name":"users.delete-permanently","action":"App\Http\Controllers\UserController@destroyPermanently"},{"host":null,"methods":["PATCH"],"uri":"users\/{slug}\/restore","name":"users.restore","action":"App\Http\Controllers\UserController@restoreDeleted"},{"host":null,"methods":["GET","HEAD"],"uri":"users","name":"users.index","action":"App\Http\Controllers\UserController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/create","name":"users.create","action":"App\Http\Controllers\UserController@create"},{"host":null,"methods":["POST"],"uri":"users","name":"users.store","action":"App\Http\Controllers\UserController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{user}","name":"users.show","action":"App\Http\Controllers\UserController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"users\/{user}\/edit","name":"users.edit","action":"App\Http\Controllers\UserController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"users\/{user}","name":"users.update","action":"App\Http\Controllers\UserController@update"},{"host":null,"methods":["DELETE"],"uri":"users\/{user}","name":"users.destroy","action":"App\Http\Controllers\UserController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"main-meta","name":"main-meta.index","action":"App\Http\Controllers\MainMetaController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"main-meta\/create","name":"main-meta.create","action":"App\Http\Controllers\MainMetaController@create"},{"host":null,"methods":["POST"],"uri":"main-meta","name":"main-meta.store","action":"App\Http\Controllers\MainMetaController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"main-meta\/{main_metum}","name":"main-meta.show","action":"App\Http\Controllers\MainMetaController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"main-meta\/{main_metum}\/edit","name":"main-meta.edit","action":"App\Http\Controllers\MainMetaController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"main-meta\/{main_metum}","name":"main-meta.update","action":"App\Http\Controllers\MainMetaController@update"},{"host":null,"methods":["DELETE"],"uri":"main-meta\/{main_metum}","name":"main-meta.destroy","action":"App\Http\Controllers\MainMetaController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"ad-block","name":"ad-block.index","action":"App\Http\Controllers\AdBlockController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"ad-block\/create","name":"ad-block.create","action":"App\Http\Controllers\AdBlockController@create"},{"host":null,"methods":["POST"],"uri":"ad-block","name":"ad-block.store","action":"App\Http\Controllers\AdBlockController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"ad-block\/{ad_block}","name":"ad-block.show","action":"App\Http\Controllers\AdBlockController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"ad-block\/{ad_block}\/edit","name":"ad-block.edit","action":"App\Http\Controllers\AdBlockController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"ad-block\/{ad_block}","name":"ad-block.update","action":"App\Http\Controllers\AdBlockController@update"},{"host":null,"methods":["DELETE"],"uri":"ad-block\/{ad_block}","name":"ad-block.destroy","action":"App\Http\Controllers\AdBlockController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"twitter-config","name":"twitter-config.index","action":"App\Http\Controllers\TwitterConfigController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"twitter-config\/create","name":"twitter-config.create","action":"App\Http\Controllers\TwitterConfigController@create"},{"host":null,"methods":["POST"],"uri":"twitter-config","name":"twitter-config.store","action":"App\Http\Controllers\TwitterConfigController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"twitter-config\/{twitter_config}","name":"twitter-config.show","action":"App\Http\Controllers\TwitterConfigController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"twitter-config\/{twitter_config}\/edit","name":"twitter-config.edit","action":"App\Http\Controllers\TwitterConfigController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"twitter-config\/{twitter_config}","name":"twitter-config.update","action":"App\Http\Controllers\TwitterConfigController@update"},{"host":null,"methods":["DELETE"],"uri":"twitter-config\/{twitter_config}","name":"twitter-config.destroy","action":"App\Http\Controllers\TwitterConfigController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"roles","name":"roles.index","action":"App\Http\Controllers\RoleController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/create","name":"roles.create","action":"App\Http\Controllers\RoleController@create"},{"host":null,"methods":["POST"],"uri":"roles","name":"roles.store","action":"App\Http\Controllers\RoleController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/{role}","name":"roles.show","action":"App\Http\Controllers\RoleController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"roles\/{role}\/edit","name":"roles.edit","action":"App\Http\Controllers\RoleController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"roles\/{role}","name":"roles.update","action":"App\Http\Controllers\RoleController@update"},{"host":null,"methods":["DELETE"],"uri":"roles\/{role}","name":"roles.destroy","action":"App\Http\Controllers\RoleController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"permissions","name":"permissions.index","action":"App\Http\Controllers\PermissionController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"permissions\/create","name":"permissions.create","action":"App\Http\Controllers\PermissionController@create"},{"host":null,"methods":["POST"],"uri":"permissions","name":"permissions.store","action":"App\Http\Controllers\PermissionController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"permissions\/{permission}","name":"permissions.show","action":"App\Http\Controllers\PermissionController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"permissions\/{permission}\/edit","name":"permissions.edit","action":"App\Http\Controllers\PermissionController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"permissions\/{permission}","name":"permissions.update","action":"App\Http\Controllers\PermissionController@update"},{"host":null,"methods":["DELETE"],"uri":"permissions\/{permission}","name":"permissions.destroy","action":"App\Http\Controllers\PermissionController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"privacy-policy\/edit","name":"privacy-policy.edit","action":"App\Http\Controllers\PrivacyPolicyController@edit"},{"host":null,"methods":["GET","HEAD"],"uri":"privacy-policy","name":"privacy-policy.index","action":"App\Http\Controllers\PrivacyPolicyController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"privacy-policy\/create","name":"privacy-policy.create","action":"App\Http\Controllers\PrivacyPolicyController@create"},{"host":null,"methods":["POST"],"uri":"privacy-policy","name":"privacy-policy.store","action":"App\Http\Controllers\PrivacyPolicyController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"privacy-policy\/{privacy_policy}","name":"privacy-policy.show","action":"App\Http\Controllers\PrivacyPolicyController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"privacy-policy\/{privacy_policy}\/edit","name":"privacy-policy.edit","action":"App\Http\Controllers\PrivacyPolicyController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"privacy-policy\/{privacy_policy}","name":"privacy-policy.update","action":"App\Http\Controllers\PrivacyPolicyController@update"},{"host":null,"methods":["DELETE"],"uri":"privacy-policy\/{privacy_policy}","name":"privacy-policy.destroy","action":"App\Http\Controllers\PrivacyPolicyController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"terms-and-conditions\/edit","name":"terms-and-conditions.edit","action":"App\Http\Controllers\TermsAndConditionsController@edit"},{"host":null,"methods":["GET","HEAD"],"uri":"terms-and-conditions","name":"terms-and-conditions.index","action":"App\Http\Controllers\TermsAndConditionsController@index"},{"host":null,"methods":["GET","HEAD"],"uri":"terms-and-conditions\/create","name":"terms-and-conditions.create","action":"App\Http\Controllers\TermsAndConditionsController@create"},{"host":null,"methods":["POST"],"uri":"terms-and-conditions","name":"terms-and-conditions.store","action":"App\Http\Controllers\TermsAndConditionsController@store"},{"host":null,"methods":["GET","HEAD"],"uri":"terms-and-conditions\/{terms_and_condition}","name":"terms-and-conditions.show","action":"App\Http\Controllers\TermsAndConditionsController@show"},{"host":null,"methods":["GET","HEAD"],"uri":"terms-and-conditions\/{terms_and_condition}\/edit","name":"terms-and-conditions.edit","action":"App\Http\Controllers\TermsAndConditionsController@edit"},{"host":null,"methods":["PUT","PATCH"],"uri":"terms-and-conditions\/{terms_and_condition}","name":"terms-and-conditions.update","action":"App\Http\Controllers\TermsAndConditionsController@update"},{"host":null,"methods":["DELETE"],"uri":"terms-and-conditions\/{terms_and_condition}","name":"terms-and-conditions.destroy","action":"App\Http\Controllers\TermsAndConditionsController@destroy"},{"host":null,"methods":["GET","HEAD"],"uri":"stats","name":"stats.index","action":"App\Http\Controllers\StatsController@index"}],
            prefix: '',

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            url: function (url, parameters) {
                parameters = parameters || [];

                var uri = url + '/' + parameters.join('/');

                return this.getCorrectUrl(uri);
            },

            toRoute : function (route, parameters) {
                var uri = this.replaceNamedParameters(route.uri, parameters);
                var qs  = this.getRouteQueryString(parameters);

                if (this.absolute && this.isOtherHost(route)){
                    return "//" + route.host + "/" + uri + qs;
                }

                return this.getCorrectUrl(uri + qs);
            },

            isOtherHost: function (route){
                return route.host && route.host != window.location.hostname;
            },

            replaceNamedParameters : function (uri, parameters) {
                uri = uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        var value = parameters[key];
                        delete parameters[key];
                        return value;
                    } else {
                        return match;
                    }
                });

                // Strip out any optional parameters that were not given
                uri = uri.replace(/\/\{.*?\?\}/g, '');

                return uri;
            },

            getRouteQueryString : function (parameters) {
                var qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            },

            getCorrectUrl: function (uri) {
                var url = this.prefix + '/' + uri.replace(/^\/?/, '');

                if ( ! this.absolute) {
                    return url;
                }

                return this.rootUrl.replace('/\/?$/', '') + url;
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            var attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        var getHtmlLink = function (url, title, attributes) {
            title      = title || url;
            attributes = getLinkAttributes(attributes);

            return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
        };

        return {
            // Generate a url for a given controller action.
            // laroute.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // laroute.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a fully qualified URL to the given path.
            // laroute.route('url', [params = {}])
            url : function (route, parameters) {
                parameters = parameters || {};

                return routes.url(route, parameters);
            },

            // Generate a html link to the given url.
            // laroute.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url = this.url(url);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given route.
            // laroute.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                var url = this.route(route, parameters);

                return getHtmlLink(url, title, attributes);
            },

            // Generate a html link to the given controller action.
            // laroute.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                var url = this.action(action, parameters);

                return getHtmlLink(url, title, attributes);
            }

        };

    }).call(this);

    /**
     * Expose the class either via AMD, CommonJS or the global object
     */
    if (typeof define === 'function' && define.amd) {
        define(function () {
            return laroute;
        });
    }
    else if (typeof module === 'object' && module.exports){
        module.exports = laroute;
    }
    else {
        window.laroute = laroute;
    }

}).call(this);

