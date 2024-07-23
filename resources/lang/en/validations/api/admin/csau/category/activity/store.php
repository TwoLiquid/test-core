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
        'required' => __('validations.api.admin.csau.category.activity.store.category_id.required'),
        'integer'  => __('validations.api.admin.csau.category.activity.store.category_id.integer'),
        'exists'   => __('validations.api.admin.csau.category.activity.store.category_id.exists')
    ],
    'subcategory_id' => [
        'integer' => __('validations.api.admin.csau.category.activity.store.subcategory_id.integer'),
        'exists'  => __('validations.api.admin.csau.category.activity.store.subcategory_id.exists')
    ],
    'name' => [
        'required' => __('validations.api.admin.csau.category.activity.store.name.required'),
        'array'    => __('validations.api.admin.csau.category.activity.store.name.array')
    ],
    'devices_ids' => [
        'array' => __('validations.api.admin.csau.category.activity.store.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.category.activity.store.devices_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.category.activity.store.devices_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.category.activity.store.devices_ids.*.exists')
        ]
    ],
    'platforms_ids' => [
        'required' => __('validations.api.admin.csau.category.activity.store.platforms_ids.required'),
        'array'    => __('validations.api.admin.csau.category.activity.store.platforms_ids.array'),
        '*' => [
            'required' => 'The platform id field is required.',
            'integer'  => __('validations.api.admin.csau.category.activity.store.platforms_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.category.activity.store.platforms_ids.*.exists')
        ]
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.category.activity.store.visible.required'),
        'boolean'  => __('validations.api.admin.csau.category.activity.store.visible.boolean')
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.csau.category.activity.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.category.activity.store.result.success')
    ]
];