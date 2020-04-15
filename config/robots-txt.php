<?php
return [
    'environments' => [
        'production' => [
            'paths' => [
                '*' => [
                    'disallow' => [
                        ''
                    ],
                    'allow' => []
                ],
            ],
            'sitemaps' => [
                'sitemap',
                'sitemap-main',
                'sitemap-users',
                'sitemap-faucets',
                'sitemap-payment-processors',
                'sitemap-users-faucets',
                'sitemap-users-rotators',
                'sitemap-users-payment-processors',
                'sitemap-alerts'
            ]
        ],
        'local' => [
            'paths' => [
                '*' => [
                    'disallow' => [
                        ''
                    ],
                    'allow' => []
                ],
            ],
            'sitemaps' => [
                'sitemap',
                'sitemap-main',
                'sitemap-users',
                'sitemap-faucets',
                'sitemap-payment-processors',
                'sitemap-users-faucets',
                'sitemap-users-rotators',
                'sitemap-users-payment-processors',
                'sitemap-alerts'
            ]
        ]
    ]
];