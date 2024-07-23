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

    'username' => [
        'required' => __('validations.api.general.dashboard.profile.update.username.required'),
        'string'   => __('validations.api.general.dashboard.profile.update.username.string'),
        'regex'    => __('validations.api.general.dashboard.profile.update.username.regex'),
        'min'      => __('validations.api.general.dashboard.profile.update.username.min'),
        'max'      => __('validations.api.general.dashboard.profile.update.username.min'),
        'unique'   => __('validations.api.general.dashboard.profile.update.username.unique')
    ],
    'gender_id' => [
        'required' => __('validations.api.general.dashboard.profile.update.gender_id.required'),
        'integer'  => __('validations.api.general.dashboard.profile.update.gender_id.integer')
    ],
    'hide_gender' => [
        'required' => __('validations.api.general.dashboard.profile.update.hide_gender.required'),
        'boolean'  => __('validations.api.general.dashboard.profile.update.hide_gender.boolean')
    ],
    'birth_date' => [
        'required'    => __('validations.api.general.dashboard.profile.update.birth_date.required'),
        'date_format' => __('validations.api.general.dashboard.profile.update.birth_date.date_format')
    ],
    'hide_age' => [
        'required' => __('validations.api.general.dashboard.profile.update.hide_age.required'),
        'boolean'  => __('validations.api.general.dashboard.profile.update.hide_age.boolean')
    ],
    'description' => [
        'string' => __('validations.api.general.dashboard.profile.update.description.string')
    ],
    'top_vybers' => [
        'required' => __('validations.api.general.dashboard.profile.update.top_vybers.required'),
        'boolean'  => __('validations.api.general.dashboard.profile.update.top_vybers.boolean')
    ],
    'hide_reviews' => [
        'required' => __('validations.api.general.dashboard.profile.update.hide_reviews.required'),
        'boolean'  => __('validations.api.general.dashboard.profile.update.hide_reviews.boolean')
    ],
    'current_country_id' => [
        'required' => __('validations.api.general.dashboard.profile.update.current_country_id.required'),
        'integer'  => __('validations.api.general.dashboard.profile.update.current_country_id.integer'),
        'exists'   => __('validations.api.general.dashboard.profile.update.current_country_id.exists')
    ],
    'current_region_id' => [
        'required' => __('validations.api.general.dashboard.profile.update.current_region_id.required'),
        'integer'  => __('validations.api.general.dashboard.profile.update.current_region_id.integer'),
        'exists'   => __('validations.api.general.dashboard.profile.update.current_region_id.exists')
    ],
    'current_city_id' => [
        'required' => __('validations.api.general.dashboard.profile.update.current_city_id.required'),
        'integer'  => __('validations.api.general.dashboard.profile.update.current_city_id.integer'),
        'exists'   => __('validations.api.general.dashboard.profile.update.current_city_id.exists')
    ],
    'languages' => [
        'array' => __('validations.api.general.dashboard.profile.update.languages.array'),
        '*' => [
            'language_id' => [
                'required' => __('validations.api.general.dashboard.profile.update.languages.*.language_id.required'),
                'integer'  => __('validations.api.general.dashboard.profile.update.languages.*.language_id.integer')
            ],
            'language_level_id' => [
                'required' => __('validations.api.general.dashboard.profile.update.languages.*.language_level_id.required'),
                'integer'  => __('validations.api.general.dashboard.profile.update.languages.*.language_level_id.integer')
            ]
        ]
    ],
    'personality_traits_ids' => [
        'array' => __('validations.api.general.dashboard.profile.update.personality_traits_ids.array'),
        '*' => [
            'required' => __('validations.api.general.dashboard.profile.update.personality_traits_ids.*.required'),
            'integer'  => __('validations.api.general.dashboard.profile.update.personality_traits_ids.*.integer')
        ]
    ],
    'avatar' => [
        'array' => __('validations.api.general.dashboard.profile.update.avatar.array'),
        'content' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.mime.string')
        ],
    ],
    'background' => [
        'array' => __('validations.api.general.dashboard.profile.update.background.array'),
        'content' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.mime.string')
        ],
    ],
    'album' => [
        'array' => __('validations.api.general.dashboard.profile.update.album.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.general.dashboard.profile.update.album.*.content.required'),
                'string'   => __('validations.api.general.dashboard.profile.update.avatar.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.general.dashboard.profile.update.album.*.extension.required'),
                'string'   => __('validations.api.general.dashboard.profile.update.avatar.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.general.dashboard.profile.update.album.*.mime.required'),
                'string'   => __('validations.api.general.dashboard.profile.update.avatar.mime.string')
            ]
        ]
    ],
    'voice_samples' => [
        'array' => __('validations.api.general.dashboard.profile.update.voice_samples.array'),
        'content' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.content.string')
        ],
        'extension' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.extension.string')
        ],
        'mime' => [
            'string' => __('validations.api.general.dashboard.profile.update.avatar.mime.string')
        ]
    ],
    'deleted_images_ids' => [
        'array' => __('validations.api.general.dashboard.profile.update.deleted_images_ids.array'),
        '*' => [
            'integer' => __('validations.api.general.dashboard.profile.update.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.general.dashboard.profile.update.deleted_videos_ids.array'),
        '*' => [
            'integer' => __('validations.api.general.dashboard.profile.update.deleted_videos_ids.*.integer')
        ]
    ],
    'deleted_avatar_id' => [
        'integer' => __('validations.api.general.dashboard.profile.update.deleted_avatar_id.integer')
    ],
    'deleted_background_id' => [
        'integer' => __('validations.api.general.dashboard.profile.update.deleted_background_id.integer')
    ],
    'deleted_voice_sample_id' => [
        'integer' => __('validations.api.general.dashboard.profile.update.deleted_voice_sample_id.integer')
    ],
    'result' => [
        'error' => [
            'profileRequest' => [
                'acceptance' => __('validations.api.general.dashboard.profile.update.result.error.profileRequest.acceptance'),
                'pending'    => __('validations.api.general.dashboard.profile.update.result.error.profileRequest.pending')
            ],
            'find' => [
                'gender' => __('validations.api.general.dashboard.profile.update.result.error.find.gender')
            ],
            'uniqueness' => [
                'username' => __('validations.api.general.dashboard.profile.update.result.error.uniqueness.username')
            ],
            'birthDate' => [
                'young' => __('validations.api.general.dashboard.profile.update.result.error.birthDate.young')
            ]
        ],
        'success' => __('validations.api.general.dashboard.profile.update.result.success')
    ]
];