<?php

return [

    // These CSS rules will be applied after the regular template CSS

    /*
        'css' => [
            '.button-content .button { background: red }',
        ],
    */

    'colors' => [

        'highlight' => '#004ca3',
        'button'    => '#004cad',

    ],

    'view' => [
        'senderName'  => 'FreeBTC Website admin',
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,

        'logo'        => [
            'path'   => env('APP_URL') . '/assets/images/logos/bitcoin_200x200.png',
            'width'  => '200px',
            'height' => '200px',
        ],

        'twitter'  => 'https://twitter.com/freebtcwebsite',
        'facebook' => 'https://www.facebook.com/freebtc.website/',
        'flickr'   => null,
    ],

];
