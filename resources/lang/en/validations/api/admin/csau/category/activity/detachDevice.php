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
        'required' => __('validations.api.admin.csau.category.activity.detachDevice.password.required'),
        'string'   => __('validations.api.admin.csau.category.activity.detachDevice.password.string')
    ],    
    'result' => [
        'error' => [
            'find' => [
                'activity' => __('validations.api.admin.csau.category.activity.detachDevice.result.error.find.activity'),
                'device'   => __('validations.api.admin.csau.category.activity.detachDevice.result.error.find.device'),
            ],
            'super'    => __('validations.api.admin.csau.category.activity.detachDevice.result.error.super'),
            'password' => __('validations.api.admin.csau.category.activity.detachDevice.result.error.password'),
        ],
        'success' => __('validations.api.admin.csau.category.activity.detachDevice.result.success')
    ]
];