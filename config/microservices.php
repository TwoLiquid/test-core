<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Microservices Config Parameters
    |--------------------------------------------------------------------------
    |
    | Each microservice contains own parameters
    |
    */

    'media' => [
        'url'         => env('MEDIA_SERVICE_URL'),
        'key'         => env('MEDIA_SERVICE_KEY'),
        'storage_url' => env('MEDIA_SERVICE_STORAGE_URL')
    ],
    'auth' => [
        'url' => env('AUTH_SERVICE_URL'),
        'key' => env('AUTH_SERVICE_KEY')
    ],
    'chat' => [
        'url' => env('CHAT_SERVICE_URL')
    ],
    'log' => [
        'url' => env('LOG_SERVICE_URL'),
        'key' => env('LOG_SERVICE_KEY')
    ],
];
