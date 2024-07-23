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
        'required' => __('validations.api.general.vybe.publishRequest.update.title.required'),
        'string'   => __('validations.api.general.vybe.publishRequest.update.title.string')
    ],
    'category_suggestion' => [
        'string' => __('validations.api.general.vybe.publishRequest.update.category_suggestion.string')
    ],
    'subcategory_suggestion' => [
        'string' => __('validations.api.general.vybe.publishRequest.update.subcategory_suggestion.string')
    ],
    'devices_ids' => [
        'array' => __('validations.api.general.vybe.publishRequest.update.devices_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.publishRequest.update.devices_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.publishRequest.update.devices_ids.*.integer')
        ]
    ],
    'device_suggestion' => [
        'string' => __('validations.api.general.vybe.publishRequest.update.device_suggestion.string')
    ],
    'activity_id' => [
        'integer' => __('validations.api.general.vybe.publishRequest.update.activity_id.integer'),
        'exists'  => __('validations.api.general.vybe.publishRequest.update.activity_id.exists')
    ],
    'activity_suggestion' => [
        'string' => __('validations.api.general.vybe.publishRequest.update.activity_suggestion.string')
    ],
    'period_id' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.period_id.required'),
        'integer'  => __('validations.api.general.vybe.publishRequest.update.period_id.integer'),
        'between'  => __('validations.api.general.vybe.publishRequest.update.period_id.between')
    ],
    'user_count' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.user_count.required'),
        'integer'  => __('validations.api.general.vybe.publishRequest.update.user_count.integer')
    ],
    // 2-nd step
    'appearance_cases' => [
        'voice_chat' => [
            'array' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.array'),
            'price' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'video_chat' => [
            'array' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.video_chat.array'),
            'price' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'platforms_ids' => [
                'array' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.array'),
                '*' => [
                    'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.*.required'),
                    'integer'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.*.integer'),
                    'exists'   => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.platforms_ids.*.exists')
                ]
            ],
            'enabled' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ],
        'real_life' => [
            'array' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.array'),
            'price' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.price.required'),
                'numeric'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.price.numeric')
            ],
            'description' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.description.string')
            ],
            'unit_id' => [
                'integer' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_id.integer'),
                'exists'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_id.exists')
            ],
            'unit_suggestion' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.unit_suggestion.string')
            ],
            'same_location' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.same_location.required'),
                'boolean'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.same_location.boolean')
            ],
            'country_id' => [
                'integer' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.country_id.integer'),
                'exists'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.country_id.exists')
            ],
            'region_id' => [
                'integer' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.region_id.integer'),
                'exists'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.region_id.exists')
            ],
            'region_suggestion' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.region_suggestion.string')
            ],
            'city_id' => [
                'integer' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.city_id.integer'),
                'exists'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.city_id.exists')
            ],
            'city_suggestion' => [
                'string' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.real_life.city_suggestion.string')
            ],
            'enabled' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.required'),
                'boolean'  => __('validations.api.general.vybe.publishRequest.update.appearance_cases.voice_chat.enabled.boolean')
            ]
        ]
    ],
    // 3-rd step
    'schedules' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.schedules.required'),
        'array'    => __('validations.api.general.vybe.publishRequest.update.schedules.array'),
        '*'        => [
            'datetime_from' => [
                'date_format' => __('validations.api.general.vybe.publishRequest.update.schedules.*.datetime_from.date_format')
            ],
            'datetime_to' => [
                'date_format' => __('validations.api.general.vybe.publishRequest.update.schedules.*.datetime_to.date_format')
            ],
        ]
    ],
    'order_advance' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.order_advance.required'),
        'integer'  => __('validations.api.general.vybe.publishRequest.update.order_advance.integer')
    ],
    // 4-th step
    'deleted_images_ids' => [
        'array' => __('validations.api.general.vybe.publishRequest.update.deleted_images_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.publishRequest.update.deleted_images_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.publishRequest.update.deleted_images_ids.*.integer')
        ]
    ],
    'deleted_videos_ids' => [
        'array' => __('validations.api.general.vybe.publishRequest.update.deleted_videos_ids.array'),
        '*' => [
            'required' => __('validations.api.general.vybe.publishRequest.update.deleted_videos_ids.*.required'),
            'integer'  => __('validations.api.general.vybe.publishRequest.update.deleted_videos_ids.*.integer')
        ]
    ],
    'files' => [
        'array' => __('validations.api.general.vybe.publishRequest.update.files.array'),
        '*' => [
            'content' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.files.*.content.required'),
                'string'   => __('validations.api.general.vybe.publishRequest.update.files.*.content.string')
            ],
            'extension' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.files.*.extension.required'),
                'string'   => __('validations.api.general.vybe.publishRequest.update.files.*.extension.string')
            ],
            'mime' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.files.*.mime.required'),
                'string'   => __('validations.api.general.vybe.publishRequest.update.files.*.mime.string')
            ],
            'original_name' => [
                'required' => __('validations.api.general.vybe.publishRequest.update.files.*.original_name.required'),
                'string'   => __('validations.api.general.vybe.publishRequest.update.files.*.original_name.string')
            ],
            'main' => [
                'boolean' => __('validations.api.general.vybe.publishRequest.update.files.*.main.boolean')
            ]
        ]
    ],
    // 5-th step
    'access_id' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.access_id.required'),
        'integer'  => __('validations.api.general.vybe.publishRequest.update.access_id.integer'),
        'between'  => __('validations.api.general.vybe.publishRequest.update.access_id.between')
    ],
    'access_password' => [
        'string' => __('validations.api.general.vybe.publishRequest.update.access_password.string')
    ],
    'showcase_id' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.showcase_id.required'),
        'integer'  => __('validations.api.general.vybe.publishRequest.update.showcase_id.integer'),
        'between'  => __('validations.api.general.vybe.publishRequest.update.showcase_id.between')
    ],
    'order_accept_id' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.order_accept_id.required'),
        'integer'  => __('validations.api.general.vybe.publishRequest.update.order_accept_id.integer'),
        'between'  => __('validations.api.general.vybe.publishRequest.update.order_accept_id.between')
    ],
    'status_id' => [
        'required' => __('validations.api.general.vybe.publishRequest.update.status_id.required'),
        'integer'  => __('validations.api.general.vybe.publishRequest.update.status_id.integer'),
        'between'  => __('validations.api.general.vybe.publishRequest.update.status_id.between')
    ],
    'result' => [
        'error' => [
            'find'      => __('validations.api.general.vybe.publishRequest.update.result.error.find'),
            'owner'     => __('validations.api.general.vybe.publishRequest.update.result.error.owner'),
            'completed' => __('validations.api.general.vybe.publishRequest.update.result.error.completed'),
            'status'    => __('validations.api.general.vybe.publishRequest.update.result.error.status'),
            'request'   => __('validations.api.general.vybe.publishRequest.update.result.error.request'),
            'activity'  => [
                'absence'  => __('validations.api.general.vybe.publishRequest.update.result.error.activity.absence'),
                'doubling' => __('validations.api.general.vybe.publishRequest.update.result.error.activity.doubling')
            ],
            'vybeType'    => __('validations.api.general.vybe.publishRequest.update.result.error.vybeType'),
            'files'       => [
                'many'    => __('validations.api.general.vybe.publishRequest.update.result.error.files.many'),
                'absence' => __('validations.api.general.vybe.publishRequest.update.result.error.files.absence')
            ],
            'accessPassword'  => [
                'required' => __('validations.api.general.vybe.publishRequest.update.result.error.accessPassword.required'),
                'excess'   => __('validations.api.general.vybe.publishRequest.update.result.error.accessPassword.excess')
            ]
        ],
        'success' => __('validations.api.general.vybe.publishRequest.update.result.success')
    ]
];