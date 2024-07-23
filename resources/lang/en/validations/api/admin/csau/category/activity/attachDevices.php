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

    'devices_ids' => [
        'array' => __('validations.api.admin.csau.category.activity.attachDevices.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.category.activity.attachDevices.devices_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.category.activity.attachDevices.devices_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.category.activity.attachDevices.devices_ids.*.exists')
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.category.activity.attachDevices.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.category.activity.attachDevices.result.success')
    ]
];