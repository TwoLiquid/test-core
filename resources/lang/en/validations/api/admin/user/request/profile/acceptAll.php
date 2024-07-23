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
                'user'           => __('validations.api.admin.user.request.profile.acceptAll.result.error.find.user'),
                'profileRequest' => __('validations.api.admin.user.request.profile.acceptAll.result.error.find.profileRequest')
            ]
        ],
        'success' => __('validations.api.admin.user.request.profile.acceptAll.result.success')
    ]
];
