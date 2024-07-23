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
        'required' => __('validations.api.general.user.detachVisitedUsers.ids.required'),
        'array'    => __('validations.api.general.user.detachVisitedUsers.ids.array'),
        '*' => [
            'required' => __('validations.api.general.user.detachVisitedUsers.ids.*.required'),
            'integer'  => __('validations.api.general.user.detachVisitedUsers.ids.*.integer'),
            'exists'   => __('validations.api.general.user.detachVisitedUsers.ids.*.exists')
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.general.user.detachVisitedUsers.result.error.find')
        ],
        'success' => __('validations.api.general.user.detachVisitedUsers.result.success')
    ]
];