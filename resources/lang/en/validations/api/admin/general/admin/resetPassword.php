<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'password' => [
        'required' => __('validations.api.admin.general.admin.resetPassword.password.required'),
        'string'   => __('validations.api.admin.general.admin.resetPassword.password.string')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.general.admin.resetPassword.result.error.find')
        ],
        'success' => __('validations.api.admin.general.admin.resetPassword.result.success')
    ]
];