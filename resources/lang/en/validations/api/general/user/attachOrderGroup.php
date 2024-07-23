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
                'user'       => __('validations.api.general.user.attachOrderGroup.result.error.find.user'),
                'orderGroup' => __('validations.api.general.user.attachOrderGroup.result.error.find.orderGroup')
            ]
        ],
        'success' => __('validations.api.general.user.attachOrderGroup.result.success')
    ]
];