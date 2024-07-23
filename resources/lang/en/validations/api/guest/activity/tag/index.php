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

    'category_id' => [
        'integer' => __('validations.api.guest.activity.tag.index.category_id.integer'),
        'exists'  => __('validations.api.guest.activity.tag.index.category_id.exists'),
    ],
    'subcategory_id' => [
        'integer' => __('validations.api.guest.activity.tag.index.subcategory_id.integer'),
        'exists'  => __('validations.api.guest.activity.tag.index.subcategory_id.exists'),
    ],
    'search' => [
        'string' => __('validations.api.guest.activity.tag.index.search.string')
    ],
    'paginated' => [
        'boolean' => __('validations.api.guest.activity.tag.index.paginated.boolean')
    ],
    'page' => [
        'integer' => __('validations.api.guest.activity.tag.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.guest.activity.tag.index.result.success')
    ]
];