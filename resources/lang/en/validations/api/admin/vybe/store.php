<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Admin Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error
    | messages used by the validator class
    |
    */

    // 1-st step
    'title' => [
        'required' => __('validations.api.admin.vybe.store.title.required'),
        'string'   => __('validations.api.admin.vybe.store.title.string')
    ],
    'category_id' => [
        'integer' => __('validations.api.admin.vybe.store.category_id.integer'),
        'exists'  => __('validations.api.admin.vybe.store.category_id.exists')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.admin.vybe.store.category_suggestion.string')
    ],
    'subcategory_id' => [
        'integer' => __('validations.api.admin.vybe.store.subcategory_id.integer'),
        'exists'  => __('validations.api.admin.vybe.store.subcategory_id.exists')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.admin.vybe.store.subcategory_suggestion.string')
    ],
    'devices_ids' => [
        'array' => __('validations.api.admin.vybe.store.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.store.devices_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.store.devices_ids.*.integer')
        ]
    ],
    'device_suggestion' => [
        'string' => __('validations.api.admin.vybe.store.device_suggestion.string')
    ],
    'activity_id' => [
        'integer' => __('validations.api.admin.vybe.store.activity_id.integer'),
        'exists'  => __('validations.api.admin.vybe.store.activity_id.exists')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.admin.vybe.store.activity_suggestion.string')
    ],
    'period_id' => [
        'required' => __('validations.api.admin.vybe.store.period_id.required'),
        'integer'  => __('validations.api.admin.vybe.store.period_id.integer'),
        'between'  => __('validations.api.admin.vybe.store.period_id.between')
    ],
    'user_count' => [
        'required' => __('validations.api.admin.vybe.store.user_count.required'),
        'integer'  => __('validations.api.admin.vybe.store.user_count.integer')
    ],
    'suspend_reason' => [
        'string' => __('validations.api.admin.vybe.store.suspend_reason.string')
    ],
    // 2-nd step
    'appearance_cases' => [
        'voice_chat' => [
            'array' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.array'),
            'price' => [
                'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.admin.vybe.store.appearance_cases.video_chat.array'),
            'price' => [
                'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.admin.vybe.store.appearance_cases.real_life.array'),
            'price' => [
                'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'same_location' => [
                'required' => __('validations.api.admin.vybe.store.appearance_cases.real_life.same_location.required'),
                'boolean'  => __('validations.api.admin.vybe.store.appearance_cases.real_life.same_location.boolean')
            ],
            'country_id' => [
                'integer' => __('validations.api.admin.vybe.store.appearance_cases.real_life.country_id.integer'),
                'exists'  => __('validations.api.admin.vybe.store.appearance_cases.real_life.country_id.exists')
            ],
            'region_id' => [
                'integer' => __('validations.api.admin.vybe.store.appearance_cases.real_life.region_id.integer'),
                'exists'  => __('validations.api.admin.vybe.store.appearance_cases.real_life.region_id.exists')
            ],
            'region_suggestion' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.real_life.region_suggestion.string')
            ],
            'city_id' => [
                'integer' => __('validations.api.admin.vybe.store.appearance_cases.real_life.city_id.integer'),
                'exists'  => __('validations.api.admin.vybe.store.appearance_cases.real_life.city_id.exists')
            ],
            'city_suggestion' => [
                'string' => __('validations.api.admin.vybe.store.appearance_cases.real_life.city_suggestion.string')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.store.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    // 3-rd step
    'schedules' => [
        'required' => __('validations.api.admin.vybe.store.schedules.required'),
        'array'    => __('validations.api.admin.vybe.store.schedules.array'),
        '*'        => [
            'day_id' => [
                'integer' => __('validations.api.admin.vybe.store.schedules.*.day_id.integer')
            ],
            'date_from' => [
                'date_format' => __('validations.api.admin.vybe.store.schedules.*.date_from.date_format')
            ],
            'date_to' => [
                'date_format' => __('validations.api.admin.vybe.store.schedules.*.date_to.date_format')
            ],
            'time_from' => [
                'required'    => __('validations.api.admin.vybe.store.schedules.*.time_from.required'),
                'date_format' => __('validations.api.admin.vybe.store.schedules.*.time_from.date_format')
            ],
            'time_to' => [
                'required'    => __('validations.api.admin.vybe.store.schedules.*.time_to.required'),
                'date_format' => __('validations.api.admin.vybe.store.schedules.*.time_to.date_format')
            ]
        ]
    ],
    'order_advance' => [
        'required' => __('validations.api.admin.vybe.store.order_advance.required'),
        'integer'  => __('validations.api.admin.vybe.store.order_advance.integer')
    ],
    // 4-th step
    'deleted_images_ids' => [
        'array' => __('validations.api.admin.vybe.store.deleted_images_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.store.deleted_images_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.store.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.admin.vybe.store.deleted_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.admin.vybe.store.deleted_videos_ids.*.required'),
            'integer'  => __('validations.api.admin.vybe.store.deleted_videos_ids.*.integer')
        ]
    ],
    'files' => [
        'required' => __('validations.api.admin.vybe.store.files.required'),
        'array'    => __('validations.api.admin.vybe.store.files.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.admin.vybe.store.files.*.content.required'),
                'string'   => __('validations.api.admin.vybe.store.files.*.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.admin.vybe.store.files.*.extension.required'),
                'string'   => __('validations.api.admin.vybe.store.files.*.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.admin.vybe.store.files.*.mime.required'),
                'string'   => __('validations.api.admin.vybe.store.files.*.mime.string')
            ],
            'original_name' => [
                'required' => __('validations.api.admin.vybe.store.files.*.original_name.required'),
                'string'   => __('validations.api.admin.vybe.store.files.*.original_name.string')
            ],
            'main' => [
                'boolean' => __('validations.api.admin.vybe.store.files.*.main.boolean')
            ]
        ]
    ],
    // 5-th step
    'access_id' => [
        'required' => __('validations.api.admin.vybe.store.access_id.required'),
        'integer'  => __('validations.api.admin.vybe.store.access_id.integer'),
        'between'  => __('validations.api.admin.vybe.store.access_id.between')
    ],
    'access_password' => [
        'string' => __('validations.api.admin.vybe.store.access_password.string')
    ],
    'showcase_id' => [
        'required' => __('validations.api.admin.vybe.store.showcase_id.required'),
        'integer'  => __('validations.api.admin.vybe.store.showcase_id.integer'),
        'between'  => __('validations.api.admin.vybe.store.showcase_id.between')
    ],
    'order_accept_id' => [
        'required' => __('validations.api.admin.vybe.store.order_accept_id.required'),
        'integer'  => __('validations.api.admin.vybe.store.order_accept_id.integer'),
        'between'  => __('validations.api.admin.vybe.store.order_accept_id.between')
    ],
    'age_limit_id' => [
        'required' => __('validations.api.admin.vybe.store.age_limit_id.required'),
        'integer'  => __('validations.api.admin.vybe.store.age_limit_id.integer'),
        'between'  => __('validations.api.admin.vybe.store.age_limit_id.between')
    ],
    'status_id' => [
        'required' => __('validations.api.admin.vybe.store.status_id.required'),
        'integer'  => __('validations.api.admin.vybe.store.status_id.integer'),
        'between'  => __('validations.api.admin.vybe.store.status_id.between')
    ],
    // settings
    'settings' => [
        'array' => __('validations.api.admin.vybe.store.settings.array')
    ],
    'result' => [
        'error' => [
            'find' => [
                'user' => __('validations.api.admin.vybe.store.result.error.find.user')
            ],
            'category' => [
                'absence'  => __('validations.api.admin.vybe.store.result.error.category.absence'),
                'doubling' => __('validations.api.admin.vybe.store.result.error.category.doubling')
            ],
            'subcategory' => [
                'doubling' => __('validations.api.admin.vybe.store.result.error.subcategory.doubling')
            ],
            'activity' => [
                'absence'  => __('validations.api.admin.vybe.store.result.error.activity.absence'),
                'doubling' => __('validations.api.admin.vybe.store.result.error.activity.doubling')
            ],
            'create' => __('validations.api.admin.vybe.store.result.error.create')
        ],
        'success' => __('validations.api.admin.vybe.store.result.success')
    ]
];