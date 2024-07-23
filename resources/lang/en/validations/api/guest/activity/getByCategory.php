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
        'boolean' => __('validations.api.guest.activity.getByCategory.paginated.boolean')
    ],
    'page' => [
        'integer' => __('validations.api.guest.activity.getByCategory.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.guest.activity.getByCategory.per_page.integer')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.guest.activity.getByCategory.result.error.find')
        ],
        'success' => __('validations.api.guest.activity.getByCategory.result.success')
    ]
];