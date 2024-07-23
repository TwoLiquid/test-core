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

    // 1-st step
    'title' => [
        'required' => __('validations.api.general.vybe.update.title.required'),
        'string'   => __('validations.api.general.vybe.update.title.string'),
        'min'      => __('validations.api.general.vybe.update.title.min'),
        'max'      => __('validations.api.general.vybe.update.title.max')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.general.vybe.update.category_suggestion.string')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.general.vybe.update.subcategory_suggestion.string')
    ],
    'devices_ids' => [
        'array' => __('validations.api.general.vybe.update.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.update.devices_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.update.devices_ids.*.integer')
        ]
    ],
    'device_suggestion' => [
        'string' => __('validations.api.general.vybe.update.device_suggestion.string')
    ],
    'activity_id' => [
        'integer' => __('validations.api.general.vybe.update.activity_id.integer'),
        'exists'  => __('validations.api.general.vybe.update.activity_id.exists')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.general.vybe.update.activity_suggestion.string')
    ],
    'period_id' => [
        'integer' => __('validations.api.general.vybe.update.period_id.integer'),
        'between' => __('validations.api.general.vybe.update.period_id.between')
    ],
    'user_count' => [
        'integer' => __('validations.api.general.vybe.update.user_count.integer')
    ],
    // 2-nd step
    'appearance_cases' => [
        'voice_chat' => [
            'array' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.array'),
            'price' => [
                'numeric' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'boolean' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.general.vybe.update.appearance_cases.video_chat.array'),
            'price' => [
                'numeric' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.update.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'boolean' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.general.vybe.update.appearance_cases.real_life.array'),
            'price' => [
                'numeric' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'same_location' => [
                'boolean' => __('validations.api.general.vybe.update.appearance_cases.real_life.same_location.boolean')
            ],
            'country_id' => [
                'integer' => __('validations.api.general.vybe.update.appearance_cases.real_life.country_id.integer'),
                'exists'  => __('validations.api.general.vybe.update.appearance_cases.real_life.country_id.exists')
            ],
            'region_id' => [
                'integer' => __('validations.api.general.vybe.update.appearance_cases.real_life.region_id.integer'),
                'exists'  => __('validations.api.general.vybe.update.appearance_cases.real_life.region_id.exists')
            ],
            'region_suggestion' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.real_life.region_suggestion.string')
            ],
            'city_id' => [
                'integer' => __('validations.api.general.vybe.update.appearance_cases.real_life.city_id.integer'),
                'exists'  => __('validations.api.general.vybe.update.appearance_cases.real_life.city_id.exists')
            ],
            'city_suggestion' => [
                'string' => __('validations.api.general.vybe.update.appearance_cases.real_life.city_suggestion.string')
            ],
            'enabled' => [
                'boolean' => __('validations.api.general.vybe.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    // 3-rd step
    'schedules' => [
        'array' => __('validations.api.general.vybe.update.schedules.array'),
        '*' => [
            'datetime_from' => [
                'date_format' => __('validations.api.general.vybe.update.schedules.*.datetime_from.date_format')
            ],
            'datetime_to' => [
                'date_format' => __('validations.api.general.vybe.update.schedules.*.datetime_to.date_format')
            ]
        ]
    ],
    'order_advance' => [
        'integer' => __('validations.api.general.vybe.update.order_advance.integer')
    ],
    // 4-th step
    'deleted_images_ids' => [
        'array' => __('validations.api.general.vybe.update.deleted_images_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.update.deleted_images_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.update.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.general.vybe.update.deleted_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.update.deleted_videos_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.update.deleted_videos_ids.*.integer')
        ]
    ],
    'files' => [
        'array' => __('validations.api.general.vybe.update.files.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.general.vybe.update.files.*.content.required'),
                'string'   => __('validations.api.general.vybe.update.files.*.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.general.vybe.update.files.*.extension.required'),
                'string'   => __('validations.api.general.vybe.update.files.*.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.general.vybe.update.files.*.mime.required'),
                'string'   => __('validations.api.general.vybe.update.files.*.mime.string')
            ],
            'original_name' => [
                'required' => __('validations.api.general.vybe.update.files.*.original_name.required'),
                'string'   => __('validations.api.general.vybe.update.files.*.original_name.string')
            ],
            'main' => [
                'boolean' => __('validations.api.general.vybe.update.files.*.main.boolean')
            ]
        ]
    ],
    // 5-th step
    'access_id' => [
        'integer' => __('validations.api.general.vybe.update.access_id.integer'),
        'between' => __('validations.api.general.vybe.update.access_id.between')
    ],
    'access_password' => [
        'string' => __('validations.api.general.vybe.update.access_password.string')
    ],
    'showcase_id' => [
        'integer' => __('validations.api.general.vybe.update.showcase_id.integer'),
        'between' => __('validations.api.general.vybe.update.showcase_id.between')
    ],
    'order_accept_id' => [
        'integer' => __('validations.api.general.vybe.update.order_accept_id.integer'),
        'between' => __('validations.api.general.vybe.update.order_accept_id.between')
    ],
    'status_id' => [
        'integer' => __('validations.api.general.vybe.update.status_id.integer'),
        'between' => __('validations.api.general.vybe.update.status_id.between')
    ],
    'result' => [
        'error' => [
            'find'      => __('validations.api.general.vybe.update.result.error.find'),
            'owner'     => __('validations.api.general.vybe.update.result.error.owner'),
            'completed' => __('validations.api.general.vybe.update.result.error.completed'),
            'status'    => __('validations.api.general.vybe.update.result.error.status'),
            'files'     => [
                'many'  => __('validations.api.general.vybe.update.result.error.files.many')
            ],
            'userCount' => [
                'max' => __('validations.api.general.vybe.update.result.error.userCount.max')
            ]
        ],
        'success' => __('validations.api.general.vybe.update.result.success')
    ]
];