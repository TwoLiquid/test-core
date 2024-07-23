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
        'boolean' => __('validations.api.guest.place.region.index.paginated.boolean')
    ],
    'page' => [
        'integer' => __('validations.api.guest.place.region.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.guest.place.region.index.per_page.integer')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.guest.place.region.index.result.error.find')
        ],
        'success' => __('validations.api.guest.place.region.index.result.success')
    ]
];