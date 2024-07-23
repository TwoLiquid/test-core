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
                'user'                => __('validations.api.admin.user.request.deactivation.index.result.error.find.user'),
                'deactivationRequest' => __('validations.api.admin.user.request.deactivation.index.result.error.find.deactivationRequest'),
            ]
        ],
        'success' => __('validations.api.admin.user.request.deactivation.index.result.success')
    ]
];
