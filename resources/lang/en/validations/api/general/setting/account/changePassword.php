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

    'password' => [
        'required' => __('validations.api.general.setting.account.changePassword.password.required'),
        'string'   => __('validations.api.general.setting.account.changePassword.password.string')
    ],
    'new_password' => [
        'required' => __('validations.api.general.setting.account.changePassword.new_password.required'),
        'string'   => __('validations.api.general.setting.account.changePassword.new_password.string')
    ],
    'result' => [
        'error' => [
            'attempts'         => __('validations.api.general.setting.account.changePassword.result.error.attempts'),
            'password'         => __('validations.api.general.setting.account.changePassword.result.error.password'),
            'passwordAttempts' => __('validations.api.general.setting.account.changePassword.result.error.passwordAttempts')
        ],
        'success' => __('validations.api.general.setting.account.changePassword.result.success')
    ]
];