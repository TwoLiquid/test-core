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

    'title' => [
        'required' => __('validations.api.admin.vybe.update.title.required'),
        'string'   => __('validations.api.admin.vybe.update.title.string'),
        'min'      => __('validations.api.admin.vybe.update.title.min'),
        'max'      => __('validations.api.admin.vybe.update.title.max')
    ],
    'activity_id' => [
        'integer'  => __('validations.api.admin.vybe.update.activity_id.integer'),
        'exists'   => __('validations.api.admin.vybe.update.activity_id.exists')
    ],
    'category_id' => [
        'integer'  => __('validations.api.admin.vybe.update.category_id.integer'),
        'exists'   => __('validations.api.admin.vybe.update.category_id.exists')
    ],
    'subcategory_id' => [
        'integer'  => __('validations.api.admin.vybe.update.subcategory_id.integer'),
        'exists'   => __('validations.api.admin.vybe.update.subcategory_id.exists')
    ],
    'devices_ids' => [
        'array'    => __('validations.api.admin.vybe.update.devices_ids.array'),
        'items' => [
            'required' => __('validations.api.admin.vybe.update.devices_ids.items.required'),
            'integer'  => __('validations.api.admin.vybe.update.devices_ids.items.integer'),
            'exists'   => __('validations.api.admin.vybe.update.devices_ids.items.exists')
        ]
    ],
    'period_id' => [
        'required' => __('validations.api.admin.vybe.update.period_id.required'),
        'integer'  => __('validations.api.admin.vybe.update.period_id.integer'),
        'between'  => __('validations.api.admin.vybe.update.period_id.between')
    ],
    'age_limit_id' => [
        'integer'  => __('validations.api.admin.vybe.update.age_limit_id.integer'),
        'between'  => __('validations.api.admin.vybe.update.age_limit_id.between')
    ],
    'status_id' => [
        'required' => __('validations.api.admin.vybe.update.status_id.required'),
        'integer'  => __('validations.api.admin.vybe.update.status_id.integer'),
        'between'  => __('validations.api.admin.vybe.update.status_id.between')
    ],
    'status_reason' => [
        'string' => __('validations.api.admin.vybe.update.status_reason.string')
    ],
    'user_count' => [
        'required' => __('validations.api.admin.vybe.update.user_count.required'),
        'integer'  => __('validations.api.admin.vybe.update.user_count.integer')
    ],
    'suspend_reason' => [
        'string' => __('validations.api.admin.vybe.update.suspend_reason.string')
    ],
    'order_advance' => [
        'required' => __('validations.api.admin.vybe.update.order_advance.required'),
        'integer'  => __('validations.api.admin.vybe.update.order_advance.integer')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.admin.vybe.update.category_suggestion.string')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.admin.vybe.update.subcategory_suggestion.string')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.admin.vybe.update.activity_suggestion.string')
    ],
    'device_suggestion' => [
        'string' => __('validations.api.admin.vybe.update.device_suggestion.string')
    ],
    'access_password' => [
        'string' => __('validations.api.admin.vybe.update.access_password.string')
    ],
    'appearance_cases' => [
        'voice_chat' => [
            'array' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.array'),
            'price' => [
                'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.admin.vybe.update.appearance_cases.video_chat.array'),
            'price' => [
                'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.admin.vybe.update.appearance_cases.real_life.array'),
            'price' => [
                'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'same_location' => [
                'required' => __('validations.api.admin.vybe.update.appearance_cases.real_life.same_location.required'),
                'boolean'  => __('validations.api.admin.vybe.update.appearance_cases.real_life.same_location.boolean')
            ],
            'country_id' => [
                'integer' => __('validations.api.admin.vybe.update.appearance_cases.real_life.country_id.integer'),
                'exists'  => __('validations.api.admin.vybe.update.appearance_cases.real_life.country_id.exists')
            ],
            'region_id' => [
                'integer' => __('validations.api.admin.vybe.update.appearance_cases.real_life.region_id.integer'),
                'exists'  => __('validations.api.admin.vybe.update.appearance_cases.real_life.region_id.exists')
            ],
            'region_suggestion' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.real_life.region_suggestion.string')
            ],
            'city_id' => [
                'integer' => __('validations.api.admin.vybe.update.appearance_cases.real_life.city_id.integer'),
                'exists'  => __('validations.api.admin.vybe.update.appearance_cases.real_life.city_id.exists')
            ],
            'city_suggestion' => [
                'string' => __('validations.api.admin.vybe.update.appearance_cases.real_life.city_suggestion.string')
            ],
            'enabled' => [
                'required' => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.admin.vybe.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    'schedules' => [
        'required' => __('validations.api.admin.vybe.update.schedules.required'),
        'array'    => __('validations.api.admin.vybe.update.schedules.array'),
        'items'    => [
            'id'     => [
                'integer' => __('validations.api.admin.vybe.update.schedules.items.id.integer'),
                'exists' => __('validations.api.admin.vybe.update.schedules.items.id.exists')
            ],
            'day_id' => [
                'integer' => __('validations.api.admin.vybe.update.schedules.items.day_id.integer'),
                'exists'  => __('validations.api.admin.vybe.update.schedules.items.day_id.exists'),
                'between' => __('validations.api.admin.vybe.update.schedules.items.day_id.between')
            ],
            'date_from' => [
                'date' => __('validations.api.admin.vybe.update.schedules.items.date_from.date')
            ],
            'date_to' => [
                'date' => __('validations.api.admin.vybe.update.schedules.items.date_to.date')
            ],
            'time_from' => [
                'required'    => __('validations.api.admin.vybe.update.schedules.items.time_from.required'),
                'date'        => __('validations.api.admin.vybe.update.schedules.items.time_from.date')
            ],
            'time_to' => [
                'required'    => __('validations.api.admin.vybe.update.schedules.items.time_to.required'),
                'date'        => __('validations.api.admin.vybe.update.schedules.items.time_to.date')
            ]
        ]
    ],
    'access_id' => [
        'required' => __('validations.api.admin.vybe.update.access_id.required'),
        'integer'  => __('validations.api.admin.vybe.update.access_id.integer'),
        'between'  => __('validations.api.admin.vybe.update.access_id.between')
    ],
    'showcase_id' => [
        'required' => __('validations.api.admin.vybe.update.showcase_id.required'),
        'integer'  => __('validations.api.admin.vybe.update.showcase_id.integer'),
        'between'  => __('validations.api.admin.vybe.update.showcase_id.between')
    ],
    'settings' => [
        'array' => __('validations.api.admin.vybe.update.settings.array')
    ],
    'media' => [
        'array' => __('validations.api.admin.vybe.update.media.array'),
        'items' => [
            'content' => [
                'required' => __('validations.api.admin.vybe.update.media.items.content.required'),
                'string'   => __('validations.api.admin.vybe.update.media.items.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.admin.vybe.update.media.items.extension.required'),
                'string'   => __('validations.api.admin.vybe.update.media.items.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.admin.vybe.update.media.items.mime.required'),
                'string'   => __('validations.api.admin.vybe.update.media.items.mime.string')
            ],
            'original_name' => [
                'required'  => __('validations.api.admin.vybe.update.media.items.original_name.required'),
                'string'    => __('validations.api.admin.vybe.update.media.items.original_name.string'),
            ],
            'main' => [
                'required'  => __('validations.api.admin.vybe.update.media.items.main.required'),
                'boolean'   => __('validations.api.admin.vybe.update.media.items.main.boolean'),
            ]
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.admin.vybe.update.deleted_videos_ids.array'),
        'items' => [
            'required' => __('validations.api.admin.vybe.update.deleted_videos_ids.items.required'),
            'integer'  => __('validations.api.admin.vybe.update.deleted_videos_ids.items.integer')
        ]
    ],
    'deleted_images_ids' => [
        'array' => __('validations.api.admin.vybe.update.deleted_images_ids.array'),
        'items' => [
            'required' => __('validations.api.admin.vybe.update.deleted_images_ids.items.required'),
            'integer'  => __('validations.api.admin.vybe.update.deleted_images_ids.items.integer')
        ]
    ],
    'result' => [
        'error' => [
            'find' => [
                'vybePeriod'        => __('validations.api.admin.vybe.update.result.error.find.vybePeriod'),
                'vybeAccess'        => __('validations.api.admin.vybe.update.result.error.find.vybeAccess'),
                'vybeShowcase'      => __('validations.api.admin.vybe.update.result.error.find.vybeShowcase'),
                'vybeStatus'        => __('validations.api.admin.vybe.update.result.error.find.vybeStatus'),
                'vybeAgeLimit'      => __('validations.api.admin.vybe.update.result.error.find.vybeAgeLimit'),
                'vybeOrderAccept'   => __('validations.api.admin.vybe.update.result.error.find.vybeOrderAccept'),
                'vybe'              => __('validations.api.admin.vybe.update.result.error.find.vybe'),
                'user'              => __('validations.api.admin.vybe.update.result.error.find.user')
            ],
            'category' => [
                'absence'  => __('validations.api.admin.vybe.update.result.error.category.absence'),
                'doubling' => __('validations.api.admin.vybe.update.result.error.category.doubling')
            ],
            'subcategory' => [
                'absence'  => __('validations.api.admin.vybe.update.result.error.subcategory.absence'),
                'doubling' => __('validations.api.admin.vybe.update.result.error.subcategory.doubling')
            ],
            'activity' => [
                'absence'  => __('validations.api.admin.vybe.update.result.error.activity.absence'),
                'doubling' => __('validations.api.admin.vybe.update.result.error.activity.doubling')
            ],
            'device' => [
                'absence'  => __('validations.api.admin.vybe.update.result.error.device.absence'),
                'doubling' => __('validations.api.admin.vybe.update.result.error.device.doubling')
            ],
            'access_password' => [
                'required'  => __('validations.api.admin.vybe.update.result.error.access_password.required')
            ],
            'vybeStatusReason' => [
                'required'  => __('validations.api.admin.vybe.update.result.error.vybeStatusReason.required')
            ],
            'csau_suggestion'  => [
                'pending'   => __('validations.api.admin.vybe.update.result.error.csau_suggestion.pending')
            ],
            'device_suggestion' => [
                'pending'   => __('validations.api.admin.vybe.update.result.error.device_suggestion.pending')
            ],
            'userCount' => [
                'max' => __('validations.api.admin.vybe.update.result.error.userCount.max')
            ],
            'create' => __('validations.api.admin.vybe.update.result.error.create')
        ],
        'success' => __('validations.api.admin.vybe.update.result.success')
    ]
];