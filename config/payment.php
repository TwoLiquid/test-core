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

    'returnUrl'       => env('PAYMENT_RETURN_URL'),
    'cancelUrl'       => env('PAYMENT_CANCEL_URL'),
    'defaultCurrency' => [
        'code' => 'RUB'
    ]
];