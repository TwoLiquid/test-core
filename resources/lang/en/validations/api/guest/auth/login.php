<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Auth Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'login' => [
        'required' => __('validations.api.guest.auth.login.login.required'),
        'string'   => __('validations.api.guest.auth.login.login.string')
    ],
    'password' => [
        'required' => __('validations.api.guest.auth.login.password.required'),
        'string'   => __('validations.api.guest.auth.login.password.string')
    ],
    'result' => [
        'error' => [
            'find'        => __('validations.api.guest.auth.login.result.error.find'),
            'attempts'    => __('validations.api.guest.auth.login.result.error.attempts'),
            'credentials' => __('validations.api.guest.auth.login.result.error.credentials')
        ],
        'success' => __('validations.api.guest.auth.login.result.success')
    ]
];