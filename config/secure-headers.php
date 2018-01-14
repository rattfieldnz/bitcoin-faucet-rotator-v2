<?php

return [

    /*
     * X-Content-Type-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     *
     * Available Value: 'nosniff'
     */

    'x-content-type-options' => 'nosniff',

    /*
     * X-Download-Options
     *
     * Reference: https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
     *
     * Available Value: 'noopen'
     */

    'x-download-options' => 'noopen',

    /*
     * X-Frame-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     *
     * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
     */

    'x-frame-options' => 'sameorigin',

    /*
     * X-Permitted-Cross-Domain-Policies
     *
     * Reference: https://www.adobe.com/devnet/adobe-media-server/articles/cross-domain-xml-for-streaming.html
     *
     * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */

    'x-permitted-cross-domain-policies' => 'none',

    /*
     * X-XSS-Protection
     *
     * Reference: https://blogs.msdn.microsoft.com/ieinternals/2011/01/31/controlling-the-xss-filter
     *
     * Available Value: '1', '0', '1; mode=block'
     */

    'x-xss-protection' => '1; mode=block',

    /*
     * Referrer-Policy
     *
     * Reference: https://w3c.github.io/webappsec-referrer-policy
     *
     * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */

    'referrer-policy' => 'no-referrer',

    /*
     * HTTP Strict Transport Security
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Please ensure your website had set up ssl/tls before enable hsts.
     */

    'hsts' => [
        'enable' => false,

        'max-age' => 15552000,

        'include-sub-domains' => false,
    ],

    /*
     * Public Key Pinning
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/Public_Key_Pinning
     *
     * hpkp will be ignored if hashes is empty.
     */

    'hpkp' => [
        'hashes' => [
            // [
            //     'algo' => 'sha256',
            //     'hash' => 'hash-value',
            // ],
        ],

        'include-sub-domains' => false,

        'max-age' => 15552000,

        'report-only' => false,

        'report-uri' => null,
    ],

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/CSP
     *
     * csp will be ignored if custom-csp is not null. To disable csp, set custom-csp to empty string.
     *
     * Note: custom-csp does not support report-only.
     */

    'custom-csp' => null,

    'csp' => [
        'report-only' => false,

        'report-uri' => null,

        'upgrade-insecure-requests' => false,

        // enable or disable the automatic conversion of sources to https
        'https-transform-on-https-connections' => false,

        'base-uri' => [
            //
        ],

        'default-src' => [
            //
        ],

        'frame-src' => [
            'allow' => [
                'www.google.com',
                'mellowads.com',
                'a-ads.com',
                'ad.a-ads.com',
                'coinurl.com',
                'bee-ads.com',
                's7.addthis.com',
                'disqus.com',
                env('DISQUS_SHORTNAME') . '.disqus.com',
                'c.disquscdn.com',
                'disqusads.com',
                'imgur.com',
                'i.imgur.com'
            ],
        ],

        'child-src' => [
            'allow' => [
                'www.google.com',
                'mellowads.com',
                'a-ads.com',
                'ad.a-ads.com',
                'coinurl.com',
                'bee-ads.com',
                's7.addthis.com',
                'disqus.com',
                env('DISQUS_SHORTNAME') . '.disqus.com',
                'c.disquscdn.com',
                'disqusads.com',
                'imgur.com',
                'i.imgur.com'
            ],
        ],

        'script-src' => [
            'allow' => [
                env('APP_URL'),
                'cloudflare.com',
                'ajax.cloudflare.com',
                'cdnjs.cloudflare.com',
                'cdn.datatables.net',
                'ajax.googleapis.com',
                'maxcdn.bootstrapcdn.com',
                'oss.maxcdn.com',
                'code.jquery.com',
                'www.google-analytics.com',
                'google-analytics.com',
                'www.google.com',
                'www.gstatic.com',
                'maps.googleapis.com',
                'cdn.ravenjs.com',
                's7.addthis.com',
                'm.addthis.com',
                'api-public.addthis.com',
                'addthis.com',
                'm.addthisedge.com',
                'www.googletagmanager.com',
                'googletagmanager.com',
                'graph.facebook.com',
                'staticxx.facebook.com',
                'widgets.pinterest.com',
                'disqus.com',
                env('DISQUS_SHORTNAME') . '.disqus.com',
                'cdn.tynt.com',
                'ic.tynt.com',
                'sc.tynt.com',
                'de.tynt.com',
                'c.disquscdn.com',
            ],

            'hashes' => [
                // ['sha256' => 'hash-value'],
            ],

            'nonces' => [
                //
            ],

            'self' => false,

            'unsafe-inline' => true,

            'unsafe-eval' => true,
        ],

        'style-src' => [
            'allow' => [
                env('APP_URL'),
                'cdnjs.cloudflare.com',
                'cdn.datatables.net',
                'ajax.googleapis.com',
                'maxcdn.bootstrapcdn.com',
                'fonts.googleapis.com',
                'fonts.gstatic.com',
                'code.ionicframework.com',
                'code.jquery.com',
                'www.google.com',
                'www.gstatic.com',
                'c.disquscdn.com',
            ],

            'self' => false,

            'unsafe-inline' => true,
        ],

        'img-src' => [
            'allow' => [
                env('APP_URL'),
                'infyom.com',
                'www.google-analytics.com',
                'google-analytics.com',
                'maps.googleapis.com',
                'secure.gravatar.com',
                'csi.gstatic.com',
                'stats.g.doubleclick.net',
                'www.google.com',
                'www.google.co.nz',
                'm.addthis.com',
                'c.disquscdn.com',
                'referrer.disqus.com',
                'cdn.tynt.com',
                'ic.tynt.com',
                'sc.tynt.com',
                'de.tynt.com',
                'i.simpli.fi',
                'imgur.com',
                'i.imgur.com',
                'bitfun.co',
                'mellowads.com'
            ],

            'types' => [
                //
            ],

            'self' => false,

            'data' => true,
        ],

        /*
         * The following directives are all use 'allow' and 'self' flag.
         *
         * Note: default value of 'self' flag is false.
         */

        'font-src' => [
            'allow' => [
                env('APP_URL'),
                'cdnjs.cloudflare.com',
                'cdn.datatables.net',
                'ajax.googleapis.com',
                'maxcdn.bootstrapcdn.com',
                'fonts.googleapis.com',
                'fonts.gstatic.com',
                'code.ionicframework.com'
            ],
        ],

        'connect-src' => [
            'allow' => [
                env('APP_URL'),
                'links.services.disqus.com',
                'm.addthis.com'
            ]
        ],

        'form-action' => [
            'allow' => [
                env('APP_URL'),
            ],
        ],

        'frame-ancestors' => [
            'allow' => [
                env('APP_URL'),
            ],
        ],

        'media-src' => [
            'allow' => [
                env('APP_URL'),
            ],
        ],

        'object-src' => [
            'allow' => [
                env('APP_URL'),
            ],
        ],

        /*
         * plugin-types only support 'allow'.
         */

        'plugin-types' => [

        ],
    ],

];
