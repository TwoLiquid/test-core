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

    'date_from' => [
        'date_format' => __('validations.api.admin.csau.suggestion.category.index.date_from.date_format')
    ],
    'date_to' => [
        'date_format' => __('validations.api.admin.csau.suggestion.category.index.date_to.date_format')
    ],
    'username' => [
        'string' => __('validations.api.admin.csau.suggestion.category.index.username.string')
    ],
    'vybe_version' => [
        'integer' => __('validations.api.admin.csau.suggestion.category.index.vybe_version.integer')
    ],
    'vybe_title' => [
        'string' => __('validations.api.admin.csau.suggestion.category.index.vybe_title.string')
    ],
    'categories_ids' => [
        'array' => __('validations.api.admin.csau.suggestion.category.index.categories_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.suggestion.category.index.categories_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.suggestion.category.index.categories_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.suggestion.category.index.categories_ids.*.exists')
        ]
    ],
    'subcategories_ids' => [
        'array' => __('validations.api.admin.csau.suggestion.category.index.subcategories_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.suggestion.category.index.subcategories_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.suggestion.category.index.subcategories_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.suggestion.category.index.subcategories_ids.*.exists')
        ]
    ],
    'activities_ids' => [
        'array' => __('validations.api.admin.csau.suggestion.category.index.activities_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.suggestion.category.index.activities_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.suggestion.category.index.activities_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.suggestion.category.index.activities_ids.*.exists')
        ]
    ],
    'unit_types_ids' => [
        'array' => __('validations.api.admin.csau.suggestion.category.index.unit_types_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.suggestion.category.index.unit_types_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.suggestion.category.index.unit_types_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.suggestion.category.index.unit_types_ids.*.exists')
        ]
    ],
    'units_ids' => [
        'array' => __('validations.api.admin.csau.suggestion.category.index.units_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.suggestion.category.index.units_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.suggestion.category.index.units_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.suggestion.category.index.units_ids.*.exists')
        ]
    ],
    'paginated' => [
        'boolean' => __('validations.api.admin.csau.suggestion.category.index.paginated.boolean')
    ],
    'page' => [
        'integer' => __('validations.api.admin.csau.suggestion.category.index.page.integer')
    ],
    'per_page' => [
        'integer' => __('validations.api.admin.csau.suggestion.category.index.per_page.integer')
    ],
    'result' => [
        'success' => __('validations.api.admin.csau.suggestion.category.index.result.success')
    ]
];