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

    'otp_password' => [
        'required' => __('validations.api.admin.auth.executeTwoFactor.otp_password.required'),
        'string'   => __('validations.api.admin.auth.executeTwoFactor.otp_password.string')
    ],
    'email' => [
        'required' => __('validations.api.admin.auth.executeTwoFactor.email.required'),
        'regex'    => __('validations.api.admin.auth.executeTwoFactor.email.regex'),
        'exists'   => __('validations.api.admin.auth.executeTwoFactor.email.exists')
    ],
    'password' => [
        'required' => __('validations.api.admin.auth.executeTwoFactor.password.required'),
        'string'   => __('validations.api.admin.auth.executeTwoFactor.password.string')
    ],
    'result' => [
        'error' => [
            'find'    => __('validations.api.admin.auth.executeTwoFactor.result.error.find'),
            'initial' => __('validations.api.admin.auth.executeTwoFactor.result.error.initial'),
            'twoFactor' => [
                'enable' => __('validations.api.admin.auth.executeTwoFactor.result.error.twoFactor.enable')
            ],
            'otpPassword' => __('validations.api.admin.auth.executeTwoFactor.result.error.otpPassword')
        ],
        'success' => __('validations.api.admin.auth.executeTwoFactor.result.success')
    ]
];