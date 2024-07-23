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
        'string' => __('validations.api.admin.csau.platform.update.name.string')
    ],
    'voice_chat' => [
        'boolean' => __('validations.api.admin.csau.platform.update.voice_chat.boolean')
    ],
    'visible_in_voice_chat' => [
        'boolean' => __('validations.api.admin.csau.platform.update.visible_in_voice_chat.boolean')
    ],
    'video_chat' => [
        'boolean' => __('validations.api.admin.csau.platform.update.video_chat.boolean')
    ],
    'visible_in_video_chat' => [
        'boolean' => __('validations.api.admin.csau.platform.update.visible_in_video_chat.boolean')
    ],
    'icon' => [
        'array'   => __('validations.api.admin.csau.platform.update.icon.array'),
        'content' => [
            'required' => __('validations.api.admin.csau.platform.update.icon.content.required'),
            'string'   => __('validations.api.admin.csau.platform.update.icon.content.string')
        ],
        'extension' => [
            'required' => __('validations.api.admin.csau.platform.update.icon.extension.required'),
            'string'   => __('validations.api.admin.csau.platform.update.icon.extension.string')
        ],
        'mime' => [
            'required' => __('validations.api.admin.csau.platform.update.icon.mime.required'),
            'string'   => __('validations.api.admin.csau.platform.update.icon.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'find' => __('validations.api.admin.csau.platform.update.result.error.find')
        ],
        'success' => __('validations.api.admin.csau.platform.update.result.success')
    ]
];