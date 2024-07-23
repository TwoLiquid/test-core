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
        'string' => __('validations.api.admin.csau.device.update.name.string')
    ],
    'visible' => [
        'boolean' => __('validations.api.admin.csau.device.update.visible.boolean')
    ],
    'icon' => [
        'array' => __('validations.api.admin.csau.device.update.icon.array'),
        'content' => [
            'string' => __('validations.api.admin.csau.device.update.icon.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.csau.device.update.icon.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.csau.device.update.icon.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.device.update.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.device.update.result.success')
    ]
];