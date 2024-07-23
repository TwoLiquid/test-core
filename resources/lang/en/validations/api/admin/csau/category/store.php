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

    'name' => [
        'required' => __('validations.api.admin.csau.category.store.name.required'),
        'array'    => __('validations.api.admin.csau.category.store.name.array')
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.category.store.visible.required'),
        'boolean'  => __('validations.api.admin.csau.category.store.visible.boolean')
    ],
    'icon' => [
        'array' => __('validations.api.admin.csau.category.store.icon.array'),
        'content'  => [
            'string' => __('validations.api.admin.csau.category.store.icon.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.csau.category.store.icon.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.csau.category.store.icon.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.csau.category.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.category.store.result.success')
    ]
];