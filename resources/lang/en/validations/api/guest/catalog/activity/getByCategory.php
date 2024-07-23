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

    'paginated' => [
        'boolean' => __('validations.api.guest.catalog.activity.getByCategory.paginated.boolean')
    ],
    'page' => [
        'integer' => __('validations.api.guest.catalog.activity.getByCategory.page.integer')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.guest.catalog.activity.getByCategory.result.error.find')
        ],
        'success' => __('validations.api.guest.catalog.activity.getByCategory.result.success')
    ]
];