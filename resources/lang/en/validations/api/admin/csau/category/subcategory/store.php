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
        'required' => __('validations.api.admin.csau.category.subcategory.store.category_id.required'),
        'integer'  => __('validations.api.admin.csau.category.subcategory.store.category_id.integer'),
        'exists'   => __('validations.api.admin.csau.category.subcategory.store.category_id.exists')
    ],
    'name' => [
        'required' => __('validations.api.admin.csau.category.subcategory.store.name.required'),
        'array'    => __('validations.api.admin.csau.category.subcategory.store.name.array')
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.category.subcategory.store.visible.required'),
        'boolean'  => __('validations.api.admin.csau.category.subcategory.store.visible.boolean')
    ],
    'icon' => [
        'array' => __('validations.api.admin.csau.category.subcategory.store.icon.array'),
        'content'  => [
            'string' => __('validations.api.admin.csau.category.subcategory.store.icon.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.csau.category.subcategory.store.icon.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.csau.category.subcategory.store.icon.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.csau.category.subcategory.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.category.subcategory.store.result.success')
    ]
];