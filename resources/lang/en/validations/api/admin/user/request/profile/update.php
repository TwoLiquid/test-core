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

    'account_status_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.account_status_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.account_status_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.account_status_status_id.in')
    ],
    'username_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.username_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.username_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.username_status_id.in')
    ],
    'birth_date_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.birth_date_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.birth_date_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.birth_date_status_id.in')
    ],
    'description_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.description_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.description_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.description_status_id.in')
    ],
    'voice_sample_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.voice_sample_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.voice_sample_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.voice_sample_status_id.in')
    ],
    'avatar_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.voice_sample_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.avatar_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.avatar_status_id.in')
    ],
    'background_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.background_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.background_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.background_status_id.in')
    ],
    'album_status_id' => [
        'required' => __('validations.api.admin.user.request.profile.update.album_status_id.required'),
        'integer'  => __('validations.api.admin.user.request.profile.update.album_status_id.integer'),
        'in'       => __('validations.api.admin.user.request.profile.update.album_status_id.in')
    ],
    'toast_message_text' => [
        'boolean' => __('validations.api.admin.user.request.profile.update.toast_message_text.boolean')
    ],
    'avatar' => [
        'array' => __('validations.api.admin.user.request.profile.update.avatar.array'),
        'content' => [
            'string' => __('validations.api.admin.user.request.profile.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.user.request.profile.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.user.request.profile.update.avatar.mime.string')
        ],
    ],
    'album' => [
        'array' => __('validations.api.admin.user.request.profile.update.album.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.admin.user.request.profile.update.album.*.content.required'),
                'string'   => __('validations.api.admin.user.request.profile.update.avatar.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.admin.user.request.profile.update.album.*.extension.required'),
                'string'   => __('validations.api.admin.user.request.profile.update.avatar.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.admin.user.request.profile.update.album.*.mime.required'),
                'string'   => __('validations.api.admin.user.request.profile.update.avatar.mime.string')
            ]
        ]
    ],
    'voice_samples' => [
        'array' => __('validations.api.admin.user.request.profile.update.voice_samples.array'),
        'content' => [
            'string' => __('validations.api.admin.user.request.profile.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.user.request.profile.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.user.request.profile.update.avatar.mime.string')
        ]
    ],
    'result' => [
        'error' => [
            'find' => [
                'user'           => __('validations.api.admin.user.request.profile.update.result.error.find.user'),
                'profileRequest' => __('validations.api.admin.user.request.profile.update.result.error.find.profileRequest')
            ],
            'unprocessed' => [
                'common' => __('validations.api.admin.user.request.profile.update.result.error.unprocessed.common'),
                'user'   => __('validations.api.admin.user.request.profile.update.result.error.unprocessed.user')
            ]
        ],
        'success' => __('validations.api.admin.user.request.profile.update.result.success')
    ]
];
