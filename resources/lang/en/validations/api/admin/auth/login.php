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

    'email' => [
        'required' => __('validations.api.admin.auth.login.email.required'),
        'regex'    => __('validations.api.admin.auth.login.email.regex'),
        'exists'   => __('validations.api.admin.auth.login.email.exists')
    ],
    'password' => [
        'required' => __('validations.api.admin.auth.login.password.required'),
        'string'   => __('validations.api.admin.auth.login.password.string')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.auth.login.result.error.find')
        ],
        'success' => __('validations.api.admin.auth.login.result.success')
    ]
];

