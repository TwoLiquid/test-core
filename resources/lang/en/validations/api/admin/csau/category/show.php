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
        'integer' => __('validations.api.admin.csau.category.show.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.csau.category.show.per_page.integer')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.csau.category.show.paginated.boolean')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.category.show.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.category.show.result.success')
    ]
];