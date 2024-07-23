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

    'otp_password' => [
        'required' => __('validations.api.general.setting.account.deactivation.otp_password.required'),
        'string'   => __('validations.api.general.setting.account.deactivation.otp_password.string')
    ],
    'reason' => [
        'string' => __('validations.api.general.setting.account.deactivation.reason.string')
    ],
    'password' => [
        'required' => __('validations.api.general.setting.account.deactivation.password.required'),
        'string'   => __('validations.api.general.setting.account.deactivation.password.string')
    ],
    'result' => [
        'success' => __('validations.api.general.setting.account.deactivation.result.success')
    ]
];