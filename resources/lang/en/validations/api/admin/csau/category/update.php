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
        'array' => __('validations.api.admin.csau.category.update.name.array')
    ],
    'visible' => [
        'boolean' => __('validations.api.admin.csau.category.update.visible.boolean')
    ],
    'icon' => [
        'array' => __('validations.api.admin.csau.category.update.icon.array'),
        'content' => [
            'string' => __('validations.api.admin.csau.category.update.icon.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.csau.category.update.icon.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.csau.category.update.icon.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.category.update.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.category.update.result.success')
    ]
];