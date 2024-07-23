<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Media Files Configs
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default media files configurations
    |
    */

    'default' => [
        'image' => [
            'allowAllMimes' => false,
            'allowedMimes'  => [
                'image/png',
                'image/jpeg',
                'image/gif'
            ]
        ],
        'video' => [
            'allowAllMimes' => false,
            'allowedMimes'  => [
                'video/mpeg',
                'video/mp4',
                'video/ogg',
                'video/webm',
                'video/quicktime'
            ]
        ],
        'audio' => [
            'allowAllMimes' => false,
            'allowedMimes'  => [
                'audio/mpeg',
                'audio/mp4',
                'video/ogg',
                'audio/webm',
                'audio/vnd.wave',
                'audio/x-wav'
            ]
        ],
        'document' => [
            'allowAllMimes' => true,
            'allowedMimes'  => []
        ],
        'icon' => [
            'allowAllMimes' => false,
            'allowedMimes'  => [
                'image/svg+xml'
            ]
        ]
    ],
    'custom' => [
        'alert' => [
            'image' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ]
            ],
            'sound' => [
                'maxSize'       => 20000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'audio/mpeg',
                    'audio/mp4',
                    'video/ogg',
                    'audio/webm',
                    'audio/vnd.wave',
                    'audio/x-wav'
                ]
            ]
        ],
        'user' => [
            'avatar' => [
                'maxSize'       => 20000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ]
            ],
            'background' => [
                'maxSize'       => 30000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg'
                ]
            ],
            'image' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ]
            ],
            'video' => [
                'maxSize'       => 50000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'video/mpeg',
                    'video/mp4',
                    'video/ogg',
                    'video/webm',
                    'video/quicktime'
                ]
            ],
            'idVerificationImage' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg'
                ]
            ],
            'voiceSample' => [
                'maxSize'       => 20000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'audio/mpeg',
                    'audio/mp4',
                    'video/ogg',
                    'audio/webm',
                    'audio/vnd.wave',
                    'audio/x-wav'
                ]
            ]
        ],
        'chatMessage' => [
            'audio' => [
                'maxSize'       => 20000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'audio/mpeg',
                    'audio/mp4',
                    'video/ogg',
                    'audio/webm',
                    'audio/vnd.wave',
                    'audio/x-wav'
                ]
            ],
            'document' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => true,
                'allowedMimes'  => []
            ],
            'image' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ]
            ],
            'video' => [
                'maxSize'       => 50000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'video/mpeg',
                    'video/mp4',
                    'video/ogg',
                    'video/webm',
                    'video/quicktime'
                ]
            ]
        ],
        'vybe' => [
            'image' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ]
            ],
            'video' => [
                'maxSize'       => 50000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'video/mpeg',
                    'video/mp4',
                    'video/ogg',
                    'video/webm',
                    'video/quicktime'
                ]
            ]
        ],
        'activity' => [
            'image' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                ]
            ]
        ],
        'category' => [
            'icon' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/svg+xml'
                ]
            ]
        ],
        'country' => [
            'icon' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/svg+xml'
                ]
            ]
        ],
        'device' => [
            'icon' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/svg+xml'
                ]
            ]
        ],
        'platform' => [
            'icon' => [
                'maxSize'       => 10000000,
                'allowAllMimes' => false,
                'allowedMimes'  => [
                    'image/svg+xml'
                ]
            ]
        ]
    ]
];