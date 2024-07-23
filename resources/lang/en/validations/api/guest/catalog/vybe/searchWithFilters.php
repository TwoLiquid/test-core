<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Guest Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'units_ids' => [
        'array' => __('validations.api.guest.catalog.vybe.searchWithFilters.units_ids.array'),
        '*' => [
            'required' => __('validations.api.guest.catalog.vybe.searchWithFilters.units_ids.*.required'),
            'integer'  => __('validations.api.guest.catalog.vybe.searchWithFilters.units_ids.*.integer'),
            'exists'   => __('validations.api.guest.catalog.vybe.searchWithFilters.units_ids.*.exists')
        ]
    ],
    'appearance_id' => [
        'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.appearance_id.integer'),
        'between' => __('validations.api.guest.catalog.vybe.searchWithFilters.appearance_id.between')
    ],
    'gender_id' => [
        'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.gender_id.integer'),
        'between' => __('validations.api.guest.catalog.vybe.searchWithFilters.gender_id.between')
    ],
    'city_place_id' => [
        'string' => __('validations.api.guest.catalog.vybe.searchWithFilters.city_place_id.string'),
        'exists'  => __('validations.api.guest.catalog.vybe.searchWithFilters.city_place_id.exists')
    ],
    'category_id' => [
        'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.category_id.integer'),
        'exists'  => __('validations.api.guest.catalog.vybe.searchWithFilters.category_id.exists')
    ],
    'subcategory_id' => [
        'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.subcategory_id.integer'),
        'exists'  => __('validations.api.guest.catalog.vybe.searchWithFilters.subcategory_id.exists')
    ],
    'personality_trait_ids' => [
        'array' => __('validations.api.guest.catalog.vybe.searchWithFilters.personality_trait_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.personality_trait_ids.*.integer')
        ]
    ],
    'activity_id' => [
        'required' => __('validations.api.guest.catalog.vybe.searchWithFilters.activity_id.required'),
        'integer'  => __('validations.api.guest.catalog.vybe.searchWithFilters.activity_id.integer'),
        'exists'   => __('validations.api.guest.catalog.vybe.searchWithFilters.activity_id.exists'),
    ],
    'types_ids' => [
        'array' => __('validations.api.guest.catalog.vybe.searchWithFilters.units_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.types_ids.*.integer'),
            'between' => __('validations.api.guest.catalog.vybe.searchWithFilters.types_ids.*.between')
        ]
    ],
    'devices_ids' => [
        'array' => __('validations.api.guest.catalog.vybe.searchWithFilters.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.guest.catalog.vybe.searchWithFilters.devices_ids.*.required'),
            'integer'  => __('validations.api.guest.catalog.vybe.searchWithFilters.devices_ids.*.integer'),
            'exists'   => __('validations.api.guest.catalog.vybe.searchWithFilters.devices_ids.*.exists')
        ]
    ],
    'platforms_ids' => [
        'array' => __('validations.api.guest.catalog.vybe.searchWithFilters.platforms_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.platforms_ids.*.integer')
        ]
    ],
    'languages_ids' => [
        'array' => __('validations.api.guest.catalog.vybe.searchWithFilters.languages_ids.array'),
        '*' => [
            'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.languages_ids.*.integer')
        ]
    ],
    'tags_ids' => [
        'array' => __('validations.api.guest.catalog.vybe.searchWithFilters.tags_ids.array'),
        '*' => [
            'required' => __('validations.api.guest.catalog.vybe.searchWithFilters.tags_ids.*.required'),
            'integer'  => __('validations.api.guest.catalog.vybe.searchWithFilters.tags_ids.*.integer'),
            'exists'   => __('validations.api.guest.catalog.vybe.searchWithFilters.tags_ids.*.exists')
        ]
    ],
    'date_min' => [
        'date_format' => __('validations.api.guest.catalog.vybe.searchWithFilters.date_min.date_format')
    ],
    'date_max' => [
        'date_format' => __('validations.api.guest.catalog.vybe.searchWithFilters.date_max.date_format')
    ],
    'price_min' => [
        'numeric' => __('validations.api.guest.catalog.vybe.searchWithFilters.price_min.numeric')
    ],
    'price_max' => [
        'numeric' => __('validations.api.guest.catalog.vybe.searchWithFilters.price_max.numeric')
    ],
    'has_all_tags' => [
        'boolean' => __('validations.api.guest.catalog.vybe.searchWithFilters.has_all_tags.boolean')
    ],
    'sort' => [
        'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.sort.integer'),
        'in'      => __('validations.api.guest.catalog.vybe.searchWithFilters.sort.in')
    ],
    'page' => [
        'integer' => __('validations.api.guest.catalog.vybe.searchWithFilters.page.integer'),
    ],
    'result' => [
        'success' => __('validations.api.guest.catalog.vybe.searchWithFilters.result.success')
    ]
];