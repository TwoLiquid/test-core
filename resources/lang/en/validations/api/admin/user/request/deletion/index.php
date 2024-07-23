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
                'user'            => __('validations.api.admin.user.request.deletion.index.result.error.find.user'),
                'deletionRequest' => __('validations.api.admin.user.request.deletion.index.result.error.find.deletionRequest'),
            ]
        ],
        'success' => __('validations.api.admin.user.request.deletion.index.result.success')
    ]
];
