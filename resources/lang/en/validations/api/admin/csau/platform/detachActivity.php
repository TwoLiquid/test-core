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
        'required' => __('validations.api.admin.csau.platform.detachActivity.password.required'),
        'string'   => __('validations.api.admin.csau.platform.detachActivity.password.string')
    ],    
    'result' => [
        'error' => [
            'find' => [
                'platform' => __('validations.api.admin.csau.platform.detachActivity.result.error.find.platform'),
                'activity' => __('validations.api.admin.csau.platform.detachActivity.result.error.find.activity')
            ],
            'super'    => __('validations.api.admin.csau.platform.detachActivity.result.error.super'),
            'password' => __('validations.api.admin.csau.platform.detachActivity.result.error.password'),
        ],
        'success' => __('validations.api.admin.csau.platform.detachActivity.result.success')
    ]
];