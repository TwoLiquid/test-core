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
        'required' => __('validations.api.admin.vybe.destroy.password.required'),
        'string'   => __('validations.api.admin.vybe.destroy.password.string')
    ],
    'result' => [
        'error' => [
            'super'    => __('validations.api.admin.vybe.destroy.result.error.super'),
            'password' => __('validations.api.admin.vybe.destroy.result.error.password'),
            'find'     => __('validations.api.admin.vybe.destroy.result.error.find'),
            'delete'   => __('validations.api.admin.vybe.destroy.result.error.delete')
        ],
        'success' => __('validations.api.admin.vybe.destroy.result.success')
    ]
];