<?php

return [
    'name' => 'konn3ct',
    'manifest' => [
        'name' => env('APP_NAME', 'My PWA App'),
        'short_name' => 'Konn3ct',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#042c69',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/assets/manifest/k72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/assets/manifest/k96.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/assets/manifest/k128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/assets/manifest/k144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/assets/manifest/k152.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/assets/manifest/k192.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/assets/manifest/k384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/assets/manifest/k512.png',
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
                'url' => '/rooms',
                'icons' => [
                    'src' => '/assets/manifest/k96.png',
                    'purpose' => 'any'
                ]
            ],
            [
                'name' => 'Join Room',
                'description' => 'Join a room',
                'url' => '/joinsession',
                'icons' => [
                    'src' => '/assets/manifest/k96.png',
                    'purpose' => 'any'
                ]
            ]
        ],
        'url'=> 'https://dev.konn3ct.net',
        'lang'=> 'English',
        'screenshots'=> [
            [
                'src'=> '/assets/manifest/s_h_1280x800.png',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '/assets/manifest/s_h_750x1334.png',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '1280x800',
                'type'=> 'image/png'
            ],
            [
                'src'=> '[Embedded]',
                'sizes'=> '750x1334',
                'type'=> 'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'1280x800',
                'type'=>'image/png'
            ],
            [
                'src'=>'[Embedded]',
                'sizes'=>'750x1334',
                'type'=>'image/png'
            ]
    ],
        'description'=>'Host your virtual events on konn3ct! Its Free!! Register Now!!!',
        'custom' => [],
    ]
];
