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
        'array' => __('validations.api.admin.csau.category.activity.update.name.array')
    ],
    'visible' => [
        'boolean' => __('validations.api.admin.csau.category.activity.update.visible.boolean')
    ],
    'activity_images' => [
        'array' => __('validations.api.admin.csau.category.activity.update.activity_images.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.admin.csau.category.activity.update.activity_images.*.content.required'),
                'string'   => __('validations.api.admin.csau.category.activity.update.activity_images.*.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.admin.csau.category.activity.update.activity_images.*.extension.required'),
                'string'   => __('validations.api.admin.csau.category.activity.update.activity_images.*.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.admin.csau.category.activity.update.activity_images.*.mime.required'),
                'string'   => __('validations.api.admin.csau.category.activity.update.activity_images.*.mime.string')
            ],
            'type' => [
                'required' => __('validations.api.admin.csau.category.activity.update.activity_images.*.type.required'),
                'in'       => __('validations.api.admin.csau.category.activity.update.activity_images.*.type.in')
            ]
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.category.activity.update.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.category.activity.update.result.success')
    ]
];