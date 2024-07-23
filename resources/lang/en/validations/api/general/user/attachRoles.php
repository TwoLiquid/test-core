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
        'required' => __('validations.api.general.user.attachRoles.ids.required'),
        'array'    => __('validations.api.general.user.attachRoles.ids.array'),
        '*' => [
            'required' => __('validations.api.general.user.attachRoles.ids.*.required'),
            'integer'  => __('validations.api.general.user.attachRoles.ids.*.integer'),
            'exists'   => __('validations.api.general.user.attachRoles.ids.*.exists')
        ]
    ],
    'detaching' => [
        'boolean' => __('validations.api.general.user.attachRoles.detaching.boolean')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.general.user.attachRoles.result.error.find')
        ],
        'success' => __('validations.api.general.user.attachRoles.result.success')
    ]
];