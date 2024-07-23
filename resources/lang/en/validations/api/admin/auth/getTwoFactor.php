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
        'required' => __('validations.api.admin.auth.getTwoFactor.email.required'),
        'regex'    => __('validations.api.admin.auth.getTwoFactor.email.regex'),
        'exists'   => __('validations.api.admin.auth.getTwoFactor.email.exists')
    ],
    'password' => [
        'required' => __('validations.api.admin.auth.getTwoFactor.password.required'),
        'string'   => __('validations.api.admin.auth.getTwoFactor.password.string')
    ],
    'result' => [
        'error' => [
            'find'    => __('validations.api.admin.auth.getTwoFactor.result.error.find'),
            'initial' => __('validations.api.admin.auth.getTwoFactor.result.error.initial'),
            'twoFactor' => [
                'enabled' => __('validations.api.admin.auth.getTwoFactor.result.error.twoFactor.enabled')
            ]
        ],
        'success' => __('validations.api.admin.auth.getTwoFactor.result.success')
    ]
];