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

    'result' => [
        'error' => [
            'super'     => __('validations.api.admin.csau.activityTag.detachActivity.result.error.super'),
            'password'  => __('validations.api.admin.csau.activityTag.detachActivity.result.error.password'),
            'find'      => [
                'activityTag' => __('validations.api.admin.csau.activityTag.detachActivity.result.error.find.activityTag'),
                'activity'    => __('validations.api.admin.csau.activityTag.detachActivity.result.error.find.activity')
            ]
        ],
        'success' => __('validations.api.admin.csau.activityTag.detachActivity.result.success')
    ]
];