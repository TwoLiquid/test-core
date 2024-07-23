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
        'required' => __('validations.api.admin.csau.platform.store.name.required'),
        'string'   => __('validations.api.admin.csau.platform.store.name.string')
    ],
    'voice_chat' => [
        'required' => __('validations.api.admin.csau.platform.store.voice_chat.required'),
        'boolean'  => __('validations.api.admin.csau.platform.store.voice_chat.boolean')
    ],
    'visible_in_voice_chat' => [
        'required' => __('validations.api.admin.csau.platform.store.visible_in_voice_chat.required'),
        'boolean'  => __('validations.api.admin.csau.platform.store.visible_in_voice_chat.boolean')
    ],
    'video_chat' => [
        'required' => __('validations.api.admin.csau.platform.store.video_chat.required'),
        'boolean'  => __('validations.api.admin.csau.platform.store.video_chat.boolean')
    ],
    'visible_in_video_chat' => [
        'required' => __('validations.api.admin.csau.platform.store.visible_in_video_chat.required'),
        'boolean'  => __('validations.api.admin.csau.platform.store.visible_in_video_chat.boolean')
    ],
    'icon' => [
        'array' => __('validations.api.admin.csau.platform.store.icon.array'),
        'content'  => [
            'string' => __('validations.api.admin.csau.platform.store.icon.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.csau.platform.store.icon.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.csau.platform.store.icon.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'create' => __('validations.api.admin.csau.platform.store.result.error.create')
        ],
        'success' => __('validations.api.admin.csau.platform.store.result.success')
    ]
];