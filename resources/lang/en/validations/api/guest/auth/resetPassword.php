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
        'required' => __('validations.api.guest.auth.resetPassword.token.required'),
        'string'   => __('validations.api.guest.auth.resetPassword.token.string'),
        'exists'   => __('validations.api.guest.auth.resetPassword.token.exists')
    ],
    'email' => [
        'required' => __('validations.api.guest.auth.resetPassword.email.required'),
        'regex'    => __('validations.api.guest.auth.resetPassword.email.regex'),
        'exists'   => __('validations.api.guest.auth.resetPassword.email.exists')
    ],
    'password' => [
        'required' => __('validations.api.guest.auth.resetPassword.password.required'),
        'string'   => __('validations.api.guest.auth.resetPassword.password.string')
    ],
    'password_confirm' => [
        'required' => __('validations.api.guest.auth.resetPassword.password_confirm.required'),
        'string'   => __('validations.api.guest.auth.resetPassword.password_confirm.string')
    ],
    'result' => [
        'error' => [
            'mismatch' => [
                'token'       => __('validations.api.guest.auth.resetPassword.result.error.mismatch.token'),
                'credentials' => __('validations.api.guest.auth.resetPassword.result.error.mismatch.credentials')
            ]
        ],
        'success' => __('validations.api.guest.auth.resetPassword.result.success')
    ]
];