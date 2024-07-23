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
        'required' => __('validations.api.guest.auth.registerEmailVerify.token.required'),
        'string'   => __('validations.api.guest.auth.registerEmailVerify.token.string'),
        'exists'   => __('validations.api.guest.auth.registerEmailVerify.token.exists')
    ],
    'email' => [
        'required' => __('validations.api.guest.auth.registerEmailVerify.email.required'),
        'regex'    => __('validations.api.guest.auth.registerEmailVerify.email.regex'),
        'exists'   => __('validations.api.guest.auth.registerEmailVerify.email.exists')
    ],
    'result' => [
        'error' => [
            'find'     => __('validations.api.guest.auth.registerEmailVerify.result.error.find'),
            'verified' => __('validations.api.guest.auth.registerEmailVerify.result.error.verified'),
            'mismatch' => __('validations.api.guest.auth.registerEmailVerify.result.error.mismatch')
        ],
        'success' => __('validations.api.guest.auth.registerEmailVerify.result.success')
    ]
];