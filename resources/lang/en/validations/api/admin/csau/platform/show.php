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
        'integer' => __('validations.api.admin.csau.platform.show.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.csau.platform.show.per_page.integer')
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.csau.platform.show.sort_by.string'),
    ],
    'sort_order' => [
        'in' => __('validations.api.admin.csau.platform.show.sort_order.in'),
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.csau.platform.show.paginated.boolean')
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.platform.show.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.platform.show.result.success')
    ]
];