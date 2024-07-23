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
        'required' => __('validations.api.admin.csau.device.store.name.required'),
        'string'   => __('validations.api.admin.csau.device.store.name.string')
    ],
    'visible' => [
        'required' => __('validations.api.admin.csau.device.store.visible.required'),
        'boolean'  => __('validations.api.admin.csau.device.store.visible.boolean')
    ],
    'icon' => [
        'array' => __('validations.api.admin.csau.device.store.icon.array'),
        'content' => [
            'string' => __('validations.api.admin.csau.device.store.icon.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.csau.device.store.icon.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.csau.device.store.icon.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.csau.device.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.device.store.result.success')
    ]
];