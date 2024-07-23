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

    'page' => [
        'integer' => __('validations.api.general.user.getUserSubscriptions.page.integer')
    ],
    'search' => [
        'string' => __('validations.api.general.user.getUserSubscriptions.search.string')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.general.user.getUserSubscriptions.result.error.find')
        ],
        'success' => __('validations.api.general.user.getUserSubscriptions.result.success')
    ]
];