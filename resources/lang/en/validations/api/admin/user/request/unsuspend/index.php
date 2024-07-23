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
                'user'             => __('validations.api.admin.user.request.unsuspend.index.result.error.find.user'),
                'unsuspendRequest' => __('validations.api.admin.user.request.unsuspend.index.result.error.find.unsuspendRequest'),
            ]
        ],
        'success' => __('validations.api.admin.user.request.unsuspend.index.result.success')
    ]
];
