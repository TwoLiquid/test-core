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
        'required' => __('validations.api.admin.csau.activityTag.store.category_id.required'),
        'integer'  => __('validations.api.admin.csau.activityTag.store.category_id.integer'),
        'exists'   => __('validations.api.admin.csau.activityTag.store.category_id.exists')
    ],
    'visible_in_category' => [
        'required' => __('validations.api.admin.csau.activityTag.store.visible_in_category.required'),
        'boolean'  => __('validations.api.admin.csau.activityTag.store.visible_in_category.boolean')
    ],
    'subcategory_id' => [
        'required' => __('validations.api.admin.csau.activityTag.store.subcategory_id.required'),
        'integer'  => __('validations.api.admin.csau.activityTag.store.subcategory_id.integer'),
        'exists'   => __('validations.api.admin.csau.activityTag.store.subcategory_id.exists')
    ],
    'visible_in_subcategory' => [
        'required' => __('validations.api.admin.csau.activityTag.store.visible_in_subcategory.required'),
        'boolean'  => __('validations.api.admin.csau.activityTag.store.visible_in_subcategory.boolean')
    ],
    'activities_ids' => [
        'required' => __('validations.api.admin.csau.activityTag.store.activities_ids.required'),
        'array'    => __('validations.api.admin.csau.activityTag.store.activities_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.csau.activityTag.store.activities_ids.*.required'),
            'integer'  => __('validations.api.admin.csau.activityTag.store.activities_ids.*.integer'),
            'exists'   => __('validations.api.admin.csau.activityTag.store.activities_ids.*.exists')
        ]
    ],
    'name' => [
        'required' => __('validations.api.admin.csau.activityTag.store.name.required'),
        'array'    => __('validations.api.admin.csau.activityTag.store.name.array')
    ],
    'result' => [
        'error' => [
            'activities' => __('validations.api.admin.csau.activityTag.store.result.error.activities'),
            'create'     => __('validations.api.admin.csau.activityTag.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.activityTag.store.result.success')
    ]
];