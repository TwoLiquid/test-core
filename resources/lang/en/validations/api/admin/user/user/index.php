<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'user_id' => [
        'integer' => __('validations.api.admin.user.user.index.user_id.integer'),
        'exists'  => __('validations.api.admin.user.user.index.user_id.exists')
    ],
    'username' => [
        'string' => __('validations.api.admin.user.user.index.username.string')
    ],
    'first_name' => [
        'string' => __('validations.api.admin.user.user.index.first_name.string')
    ],
    'last_name' => [
        'string' => __('validations.api.admin.user.user.index.last_name.string')
    ],
    'country_id' => [
        'integer' => __('validations.api.admin.user.user.index.country_id.integer'),
        'exists'  => __('validations.api.admin.user.user.index.country_id.exists')
    ],
    'followers' => [
        'integer' => __('validations.api.admin.user.user.index.followers.integer')
    ],
    'date_from' => [
        'string'      => __('validations.api.admin.user.user.index.date_from.string'),
        'date_format' => __('validations.api.admin.user.user.index.date_from.date_format')
    ],
    'date_to' => [
        'string'      => __('validations.api.admin.user.user.index.date_to.string'),
        'date_format' => __('validations.api.admin.user.user.index.date_to.date_format')
    ],
    'statuses_ids' => [
        'array' => __('validations.api.admin.user.user.index.statuses_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.user.index.statuses_ids.*.integer'),
            'between' => __('validations.api.admin.user.user.index.statuses_ids.*.between')
        ]
    ],
    'user_balance_type_id' => [
        'integer' => __('validations.api.admin.user.user.index.user_balance_type_id.integer'),
        'between' => __('validations.api.admin.user.user.index.user_balance_type_id.between')
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.user.user.index.sort_by.string'),
        'in'     => __('validations.api.admin.user.user.index.sort_by.in')
    ],
    'sort_order' => [
        'string' => __('validations.api.admin.user.user.index.sort_order.string'),
        'in'     => __('validations.api.admin.user.user.index.sort_order.in')
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.user.user.index.paginated.boolean')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.user.user.index.per_page.integer')
    ],
    'page' => [
        'integer' => __('validations.api.admin.user.user.index.page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.user.user.index.result.success')
    ]
];