<?php

return [
    'role_structure' => [
        'owner' => [
            'users' => 'c,r,u,sd,pd,rs',
            'faucets' => 'c,r,u,sd,pd,rs',
            'payment-processors' => 'c,r,u,sd,pd,rs',
            'twitter-config' => 'c,r,u,sd,pd,rs',
            'main-meta' => 'c,r,u,sd,pd,rs',
            'ad-block' => 'c,r,u,sd,pd,rs',
            'roles' => 'r,u',
            'permissions' => 'r,u'
        ],
        'user' => [
            'users' => 'r',
            'faucets' => 'r',
            'user-faucets' => 'c,r,u,pd,sd,rs',
            'payment-processors' => 'r'
        ],
    ],
    'permission_structure' => [
        'owner' => [
            'users' => 'c,r,u,sd,pd,rs',
            'faucets' => 'c,r,u,sd,pd,rs',
            'payment-processors' => 'c,r,u,sd,pd,rs',
            'twitter-config' => 'c,r,u,sd,pd,rs',
            'main-meta' => 'c,r,u,sd,pd,rs',
            'ad-block' => 'c,r,u,sd,pd,rs',
            'roles' => 'r,u',
            'permissions' => 'r,u'
        ],
        'user' => [
            'users' => 'r',
            'faucets' => 'r',
            'user-faucets' => 'c,r,u,sd,pd,rs',
            'payment-processors' => 'r'
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'sd' => 'soft-delete',
        'pd' => 'permanent-delete',
        'rs' => 'restore'
    ]
];
