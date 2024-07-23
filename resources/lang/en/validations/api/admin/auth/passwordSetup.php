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
        'required' => __('validations.api.admin.auth.passwordSetup.token.required'),
        'string'   => __('validations.api.admin.auth.passwordSetup.token.string'),
        'exists'   => __('validations.api.admin.auth.passwordSetup.token.exists')
    ],
    'email' => [
        'required' => __('validations.api.admin.auth.passwordSetup.email.required'),
        'regex'    => __('validations.api.admin.auth.passwordSetup.email.regex'),
        'exists'   => __('validations.api.admin.auth.passwordSetup.email.exists')
    ],
    'password' => [
        'required' => __('validations.api.admin.auth.passwordSetup.password.required'),
        'string'   => __('validations.api.admin.auth.passwordSetup.password.string')
    ],
    'password_confirm' => [
        'required' => __('validations.api.admin.auth.passwordSetup.password_confirm.required'),
        'string'   => __('validations.api.admin.auth.passwordSetup.password_confirm.string')
    ],
    'result' => [
        'error' => [
            'mismatch' => [
                'token'       => __('validations.api.admin.auth.passwordSetup.result.error.mismatch.token'),
                'credentials' => __('validations.api.admin.auth.passwordSetup.result.error.mismatch.credentials')
            ]
        ],
        'success' => __('validations.api.admin.auth.passwordSetup.result.success')
    ]
];