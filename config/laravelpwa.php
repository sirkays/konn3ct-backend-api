<?php

return [
    'name' => 'konn3ct',
    'manifest' => [
        'name' => env('APP_NAME', 'My PWA App'),
        'short_name' => 'PWA',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#35ac39',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/assets/images/konn3ctIcon.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/assets/images/konn3ct_logo.png',
            '750x1334' => '/assets/images/konn3ct_logo.png',
            '828x1792' => '/assets/images/konn3ct_logo.png',
            '1125x2436' => '/assets/images/konn3ct_logo.png',
            '1242x2208' => '/assets/images/konn3ct_logo.png',
            '1242x2688' => '/assets/images/konn3ct_logo.png',
            '1536x2048' => '/assets/images/konn3ct_logo.png',
            '1668x2224' => '/assets/images/konn3ct_logo.png',
            '1668x2388' => '/assets/images/konn3ct_logo.png',
            '2048x2732' => '/assets/images/konn3ct_logo.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Rooms',
                'description' => 'Manage your rooms',
                'url' => '/room',
                'icons' => [
                    "src" => "/assets/images/konn3ctIcon.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Join Room',
                'description' => 'Join a room',
                'url' => '/joinsession'
            ]
        ],
        'custom' => []
    ]
];
