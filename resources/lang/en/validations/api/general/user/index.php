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
        'boolean' => __('validations.api.general.user.index.paginated.boolean')
    ],
    'page' => [
        'integer' => __('validations.api.general.user.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.general.user.index.per_page.integer')
    ],
    'search' => [
        'string' => __('validations.api.general.user.index.search.string')
    ],
    'only_buyers' => [
        'integer' => __('validations.api.general.user.index.only_buyers.integer')
    ],
    'only_sellers' => [
        'integer' => __('validations.api.general.user.index.only_sellers.integer')
    ],
    'only_affiliates' => [
        'integer' => __('validations.api.general.user.index.only_affiliates.integer')
    ],
    'account_id' => [
        'integer' => __('validations.api.general.user.index.account_id.integer'),
        'exists'  => __('validations.api.general.user.index.account_id.exists')
    ],
    'sort_by' => [
        'string' => __('validations.api.general.user.index.sort_by.string')
    ],
    'sort_order' => [
        'in' => __('validations.api.general.user.index.sort_order.in')
    ],
    'result' => [
        'success' => __('validations.api.general.user.index.result.success')
    ]
];