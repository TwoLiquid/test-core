<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Auth Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    'account_status_id' => [
        'required' => __('validations.api.admin.user.user.update.account_status_id.required'),
        'integer'  => __('validations.api.admin.user.user.update.account_status_id.integer')
    ],
    'seller_status_id' => [
        'required' => __('validations.api.admin.user.user.update.seller_status_id.required'),
        'integer'  => __('validations.api.admin.user.user.update.seller_status_id.integer')
    ],
    'affiliate_status_id' => [
        'required' => __('validations.api.admin.user.user.update.affiliate_status_id.required'),
        'integer'  => __('validations.api.admin.user.user.update.affiliate_status_id.integer')
    ],
    'language_id' => [
        'required' => __('validations.api.admin.user.user.update.language_id.required'),
        'integer'  => __('validations.api.admin.user.user.update.language_id.integer')
    ],
    'currency_id' => [
        'required' => __('validations.api.admin.user.user.update.currency_id.required'),
        'integer'  => __('validations.api.admin.user.user.update.currency_id.integer')
    ],
    'timezone_city_place_id' => [
        'required' => __('validations.api.admin.user.user.update.timezone_city_place_id.required'),
        'string'   => __('validations.api.admin.user.user.update.timezone_city_place_id.string')
    ],
    'username' => [
        'required' => __('validations.api.admin.user.user.update.username.required'),
        'string'   => __('validations.api.admin.user.user.update.username.string')
    ],
    'email' => [
        'required' => __('validations.api.admin.user.user.update.email.required'),
        'regex'    => __('validations.api.admin.user.user.update.email.regex')
    ],
    'password' => [
        'string'   => __('validations.api.admin.user.user.update.password.string')
    ],
    'gender_id' => [
        'required' => __('validations.api.admin.user.user.update.gender_id.required'),
        'integer'  => __('validations.api.admin.user.user.update.gender_id.integer')
    ],
    'hide_gender' => [
        'required' => __('validations.api.admin.user.user.update.hide_gender.required'),
        'boolean'  => __('validations.api.admin.user.user.update.hide_gender.boolean')
    ],
    'birth_date' => [
        'required' => __('validations.api.admin.user.user.update.birth_date.required'),
        'string'   => __('validations.api.admin.user.user.update.birth_date.string')
    ],
    'hide_age' => [
        'required' => __('validations.api.admin.user.user.update.hide_age.required'),
        'boolean'  => __('validations.api.admin.user.user.update.hide_age.boolean')
    ],
    'verified_partner' => [
        'required' => __('validations.api.admin.user.user.update.verified_partner.required'),
        'boolean'  => __('validations.api.admin.user.user.update.verified_partner.boolean')
    ],
    'streamer_badge' => [
        'required' => __('validations.api.admin.user.user.update.streamer_badge.required'),
        'boolean'  => __('validations.api.admin.user.user.update.streamer_badge.boolean')
    ],
    'streamer_milestone' => [
        'required' => __('validations.api.admin.user.user.update.streamer_milestone.required'),
        'boolean'  => __('validations.api.admin.user.user.update.streamer_milestone.boolean')
    ],
    'current_city_place_id' => [
        'required' => __('validations.api.admin.user.user.update.current_city_place_id.required'),
        'string'   => __('validations.api.admin.user.user.update.current_city_place_id.string'),
        'exists'   => __('validations.api.admin.user.user.update.current_city_place_id.exists')
    ],
    'hide_location' => [
        'required' => __('validations.api.admin.user.user.update.hide_location.required'),
        'boolean'  => __('validations.api.admin.user.user.update.hide_location.boolean')
    ],
    'languages' => [
        'array' => __('validations.api.admin.user.user.update.languages.array'),
        '*' => [
            'language_id' => [
                'required' => __('validations.api.admin.user.user.update.language_id.required'),
                'integer'  => __('validations.api.admin.user.user.update.language_id.integer')
            ],
            'language_level_id' => [
                'required' => __('validations.api.admin.user.user.update.languages.*.language_level_id.required'),
                'integer'  => __('validations.api.admin.user.user.update.languages.*.language_level_id.integer')
            ]
        ]
    ],
    'personality_traits_ids' => [
        'array' => __('validations.api.admin.user.user.update.personality_traits_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.user.user.update.personality_traits_ids.*.required'),
            'integer'  => __('validations.api.admin.user.user.update.personality_traits_ids.*.integer')
        ]
    ],
    'description' => __('validations.api.admin.user.user.update.description'),
    'avatar' => [
        'array' => __('validations.api.admin.user.user.update.avatar.array'),
        'content' => [
            'string' => __('validations.api.admin.user.user.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.user.user.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.user.user.update.avatar.mime.string')
        ],
    ],
    'background' => [
        'array' => __('validations.api.admin.user.user.update.background.array'),
        'content' => [
            'string' => __('validations.api.admin.user.user.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.user.user.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.user.user.update.avatar.mime.string')
        ],
    ],
    'album' => [
        'array' => __('validations.api.admin.user.user.update.album.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.admin.user.user.update.album.*.content.required'),
                'string'   => __('validations.api.admin.user.user.update.avatar.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.admin.user.user.update.album.*.extension.required'),
                'string'   => __('validations.api.admin.user.user.update.avatar.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.admin.user.user.update.album.*.mime.required'),
                'string'   => __('validations.api.admin.user.user.update.avatar.mime.string')
            ]
        ]
    ],
    'voice_samples' => [
        'array' => __('validations.api.admin.user.user.update.voice_samples.array'),
        'content' => [
            'string' => __('validations.api.admin.user.user.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.admin.user.user.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.admin.user.user.update.avatar.mime.string')
        ]
    ],
    'deleted_images_ids' => [
        'array' => __('validations.api.admin.user.user.update.deleted_images_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.user.update.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.admin.user.user.update.deleted_videos_ids.array'),
        '*' => [
            'integer' => __('validations.api.admin.user.user.update.deleted_videos_ids.*.integer')
        ]
    ],
    'deleted_avatar_id' => [
        'integer' => __('validations.api.admin.user.user.update.deleted_avatar_id.integer')
    ],
    'deleted_background_id' => [
        'integer' => __('validations.api.admin.user.user.update.deleted_background_id.integer')
    ],
    'deleted_voice_sample_id' => [
        'integer' => __('validations.api.admin.user.user.update.deleted_voice_sample_id.integer')
    ],
    'result' => [
        'error' => [
            'find' => [
                'user' => __('validations.api.admin.user.user.update.result.error.find.user')
            ],
            'uniqueness' => [
                'username' => [
                    'user'    => __('validations.api.admin.user.user.update.result.error.uniqueness.username.user'),
                    'pending' => __('validations.api.admin.user.user.update.result.error.uniqueness.username.pending')
                ],
                'email' => __('validations.api.admin.user.user.update.result.error.uniqueness.email')
            ]
        ],
        'success' => __('validations.api.admin.user.user.update.result.success')
    ]
];