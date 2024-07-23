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
        'required' => __('validations.api.admin.csau.category.activity.detachPlatform.password.required'),
        'string'   => __('validations.api.admin.csau.category.activity.detachPlatform.password.string')
    ],    
    'result' => [
        'error' => [
            'find' => [
                'activity' => __('validations.api.admin.csau.category.activity.detachPlatform.result.error.find.activity'),
                'platform' => __('validations.api.admin.csau.category.activity.detachPlatform.result.error.find.platform'),
            ],
            'super'    => __('validations.api.admin.csau.category.activity.detachPlatform.result.error.super'),
            'password' => __('validations.api.admin.csau.category.activity.detachPlatform.result.error.password'),
        ],
        'success' => __('validations.api.admin.csau.category.activity.detachPlatform.result.success')
    ]
];