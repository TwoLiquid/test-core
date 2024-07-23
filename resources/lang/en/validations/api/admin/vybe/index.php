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

    'vybe_id' => [
        'integer' => __('validations.api.admin.vybe.index.vybe_id.integer'),
        'exists'  => __('validations.api.admin.vybe.index.vybe_id.exists')
    ],
    'categories_ids' => [
        'array' => __('validations.api.admin.vybe.index.categories_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.vybe.index.categories_ids.*.integer'),
            'exists'  => __('validations.api.admin.vybe.index.categories_ids.*.exists')
        ]
    ],
    'subcategories_ids' => [
        'array' => __('validations.api.admin.vybe.index.subcategories_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.vybe.index.subcategories_ids.*.integer'),
            'exists'  => __('validations.api.admin.vybe.index.subcategories_ids.*.exists')
        ]
    ],
    'activities_ids' => [
        'array' => __('validations.api.admin.vybe.index.activities_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.vybe.index.activities_ids.*.integer'),
            'exists'  => __('validations.api.admin.vybe.index.activities_ids.*.exists')
        ]
    ],
    'types_ids' => [
        'array' => __('validations.api.admin.vybe.index.types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.vybe.index.types_ids.*.integer'),
            'between' => __('validations.api.admin.vybe.index.types_ids.*.between')
        ]
    ],
    'users_ids' => [
        'array' => __('validations.api.admin.vybe.index.users_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.vybe.index.users_ids.*.integer'),
            'exists'  => __('validations.api.admin.vybe.index.users_ids.*.exists')
        ]
    ],
    'vybe_title' => [
        'string' => __('validations.api.admin.vybe.index.vybe_title.string'),
    ],
    'price' => [
        'numeric' => __('validations.api.admin.vybe.index.price.numeric')
    ],
    'units_ids' => [
        'array' => __('validations.api.admin.vybe.index.units_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.vybe.index.units_ids.*.integer'),
            'exists'  => __('validations.api.admin.vybe.index.units_ids.*.exists')
        ]
    ],
    'statuses_ids' => [
        'array' => __('validations.api.admin.vybe.index.types_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.vybe.index.statuses_ids.*.integer'),
            'between' => __('validations.api.admin.vybe.index.statuses_ids.*.between')
        ]
    ],
    'sort_by' => [
        'string' => __('validations.api.admin.vybe.index.sort_by.string'),
    ],
    'sort_order' => [
        'in' => __('validations.api.admin.vybe.index.sort_order.in'),
    ],
    'page' => [
        'integer' => __('validations.api.admin.vybe.index.page.integer'),
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.vybe.index.per_page.integer'),
    ],
    'result' => [
        'success' => __('validations.api.admin.vybe.index.result.success')
    ]
];