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
        'required' => __('validations.api.admin.csau.category.activity.detachUnit.password.required'),
        'string'   => __('validations.api.admin.csau.category.activity.detachUnit.password.string')
    ],    
    'result' => [
        'error' => [
            'find' => [
                'activity' => __('validations.api.admin.csau.category.activity.detachUnit.result.error.find.activity'),
                'unit'     => __('validations.api.admin.csau.category.activity.detachUnit.result.error.find.unit'),
            ],
            'super' => __('validations.api.admin.csau.category.activity.detachUnit.result.error.super'),
            'password' => __('validations.api.admin.csau.category.activity.detachUnit.result.error.password'),
        ],
        'success' => __('validations.api.admin.csau.category.activity.detachUnit.result.success')
    ]
];