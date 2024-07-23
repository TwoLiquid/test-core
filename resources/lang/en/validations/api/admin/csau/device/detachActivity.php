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
        'required' => __('validations.api.admin.csau.device.detachActivity.password.required'),
        'string'   => __('validations.api.admin.csau.device.detachActivity.password.string')
    ],    
    'result' => [
        'error' => [
            'find' => [
                'device'   => __('validations.api.admin.csau.device.detachActivity.result.error.find.device'),
                'activity' => __('validations.api.admin.csau.device.detachActivity.result.error.find.activity')
            ],
            'super'    => __('validations.api.admin.csau.device.detachActivity.result.error.super'),
            'password' => __('validations.api.admin.csau.device.detachActivity.result.error.password'),
        ],
        'success' => __('validations.api.admin.csau.device.detachActivity.result.success')
    ]
];