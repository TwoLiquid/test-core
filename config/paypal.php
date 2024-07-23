<?php

return [
    'sandbox' => [
        'auth_token_url' => env('PAYPAL_SANDBOX_AUTH_TOKEN_URL')
    ],
	'client_id'   => env('PAYPAL_CLIENT_ID', ''),
	'secret'      => env('PAYPAL_SECRET', ''),
	'environment' => env('PAYPAL_MODE', 'sandbox'),
    'settings'    => []
];
