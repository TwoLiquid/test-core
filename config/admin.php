<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Root Admin Config Parameters
    |--------------------------------------------------------------------------
    |
    */

    'root' => [
        'last_name'  => env('ROOT_ADMIN_LAST_NAME'),
        'first_name' => env('ROOT_ADMIN_FIRST_NAME'),
        'email'      => env('ROOT_ADMIN_EMAIL'),
        'password'   => env('ROOT_ADMIN_PASSWORD')
    ],
    'login' => [
        'url' => env('ADMIN_LOGIN_URL')
    ],
    'password' => [
        'reset' => [
            'url' => env('ADMIN_PASSWORD_RESET_URL')
        ]
    ]
];
