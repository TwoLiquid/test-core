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

    'token' => [
        'required' => __('validations.auth.registerEmailVerify.token.required'),
        'string'   => __('validations.auth.registerEmailVerify.token.string'),
        'exists'   => __('validations.auth.registerEmailVerify.token.exists')
    ],
    'email' => [
        'required' => __('validations.auth.registerEmailVerify.email.required'),
        'email'    => __('validations.auth.registerEmailVerify.email.email'),
        'exists'   => __('validations.auth.registerEmailVerify.email.exists')
    ],
    'result' => [
        'error' => [
            'mismatch' => __('validations.auth.registerEmailVerify.result.error.mismatch')
        ],
        'success' => __('validations.auth.registerEmailVerify.result.success')
    ]
];