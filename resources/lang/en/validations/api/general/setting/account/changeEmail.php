<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api General Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'email' => [
        'required' => __('validations.api.general.setting.account.changeEmail.email.required'),
        'regex'    => __('validations.api.general.setting.account.changeEmail.email.regex'),
        'unique'   => __('validations.api.general.setting.account.changeEmail.email.unique')
    ],
    'password' => [
        'required' => __('validations.api.general.setting.account.changeEmail.password.required'),
        'string'   => __('validations.api.general.setting.account.changeEmail.password.string')
    ],
    'result' => [
        'error' => [
            'valid'            => __('validations.api.general.setting.account.changeEmail.result.error.valid'),
            'attempts'         => __('validations.api.general.setting.account.changeEmail.result.error.attempts'),
            'password'         => __('validations.api.general.setting.account.changeEmail.result.error.password'),
            'passwordAttempts' => __('validations.api.general.setting.account.changeEmail.result.error.passwordAttempts'),
            'exists'           => __('validations.api.general.setting.account.changeEmail.email.unique')
        ],
        'success' => __('validations.api.general.setting.account.changeEmail.result.success')
    ]
];