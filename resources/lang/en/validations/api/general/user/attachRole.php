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
            'find' => [
                'user' => __('validations.api.general.user.attachRole.result.error.find.user'),
                'role' => __('validations.api.general.user.attachRole.result.error.find.role')
            ]
        ],
        'success' => __('validations.api.general.user.attachRole.result.success')
    ]
];