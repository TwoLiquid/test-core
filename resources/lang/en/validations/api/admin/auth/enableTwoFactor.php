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

    'secret_key' => [
        'required' => __('validations.api.admin.auth.enableTwoFactor.secret_key.required'),
        'string'   => __('validations.api.admin.auth.enableTwoFactor.secret_key.string')
    ],
    'email' => [
        'required' => __('validations.api.admin.auth.enableTwoFactor.email.required'),
        'regex'    => __('validations.api.admin.auth.enableTwoFactor.email.regex'),
        'exists'   => __('validations.api.admin.auth.enableTwoFactor.email.exists')
    ],
    'password' => [
        'required' => __('validations.api.admin.auth.enableTwoFactor.password.required'),
        'string'   => __('validations.api.admin.auth.enableTwoFactor.password.string')
    ],
    'result' => [
        'error' => [
            'find'    => __('validations.api.admin.auth.enableTwoFactor.result.error.find'),
            'initial' => __('validations.api.admin.auth.enableTwoFactor.result.error.initial'),
            'twoFactor' => [
                'enabled' => __('validations.api.admin.auth.enableTwoFactor.result.error.twoFactor.enabled')
            ]
        ],
        'success' => __('validations.api.admin.auth.enableTwoFactor.result.success')
    ]
];