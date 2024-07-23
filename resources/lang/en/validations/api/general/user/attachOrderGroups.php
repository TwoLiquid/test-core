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

    'ids' => [
        'required' => __('validations.api.general.user.attachOrderGroups.ids.required'),
        'array'    => __('validations.api.general.user.attachOrderGroups.ids.array'),
        '*' => [
            'required' => __('validations.api.general.user.attachOrderGroups.ids.*.required'),
            'integer'  => __('validations.api.general.user.attachOrderGroups.ids.*.integer'),
            'exists'   => __('validations.api.general.user.attachOrderGroups.ids.*.exists')
        ]
    ],
    'detaching' => [
        'boolean' => __('validations.api.general.user.attachOrderGroups.detaching.boolean')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.general.user.attachOrderGroups.result.error.find')
        ],
        'success' => __('validations.api.general.user.attachOrderGroups.result.success')
    ]
];