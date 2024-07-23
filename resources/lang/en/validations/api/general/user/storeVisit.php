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
                'user'        => __('validations.api.general.user.storeVisit.result.error.find.user'),
                'visitedUser' => __('validations.api.general.user.storeVisit.result.error.find.visitedUser')
            ]
        ],
        'success' => __('validations.api.general.user.storeVisit.result.success')
    ]
];