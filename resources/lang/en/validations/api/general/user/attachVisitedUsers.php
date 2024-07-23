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
        'required' => __('validations.api.general.user.attachVisitedUsers.ids.required'),
        'array'    => __('validations.api.general.user.attachVisitedUsers.ids.array'),
        '*' => [
            'required' => __('validations.api.general.user.attachVisitedUsers.ids.*.required'),
            'integer'  => __('validations.api.general.user.attachVisitedUsers.ids.*.integer'),
            'exists'   => __('validations.api.general.user.attachVisitedUsers.ids.*.exists')
        ]
    ],
    'detaching' => [
        'boolean' => __('validations.api.general.user.attachVisitedUsers.detaching.boolean')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.general.user.attachVisitedUsers.result.error.find')
        ],
        'success' => __('validations.api.general.user.attachVisitedUsers.result.success')
    ]
];