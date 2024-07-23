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

    'reason' => [
        'string' => __('validations.api.general.setting.account.deletion.reason.string')
    ],
    'result' => [
        'error' => [
            'password' => __('validations.api.general.setting.account.deletion.result.error.password'),
            'exists'   => __('validations.api.general.setting.account.deletion.result.error.exists')
        ],
        'success' => __('validations.api.general.setting.account.deletion.result.success')
    ]
];