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
        'required' => __('validations.api.admin.csau.category.activity.detachActivityTag.password.required'),
        'string'   => __('validations.api.admin.csau.category.activity.detachActivityTag.password.string')
    ],
    'result' => [
        'error' => [
            'find' => [
                'activity'    => __('validations.api.admin.csau.category.activity.detachActivityTag.result.error.find.activity'),
                'activityTag' => __('validations.api.admin.csau.category.activity.detachActivityTag.result.error.find.activityTag'),
            ],
            'super' => __('validations.api.admin.csau.category.activity.detachActivityTag.result.error.super'),
        ],
        'success' => __('validations.api.admin.csau.category.activity.detachActivityTag.result.success')
    ]
];