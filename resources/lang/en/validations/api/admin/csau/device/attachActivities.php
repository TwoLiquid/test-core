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

    'activities_ids' => [
        'array' => __('validations.api.admin.csau.device.attachActivities.activities_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.device.attachActivities.activities_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.device.attachActivities.activities_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.device.attachActivities.activities_ids.*.exists')
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.device.attachActivities.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.device.attachActivities.result.success')
    ]
];